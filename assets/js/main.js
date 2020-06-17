(function($) {
    $(document).ready(function(){

        const rssFeedResponse = $('.rss-feeds__flash');
        const rssFeed = $('.rss-feeds');
        const table =  $('.rss-feeds__table');

        const add = $('#add');

        //Inits show by first retrieving whats in the database
        showLinks();

        //Allows Link to be added to the database
        add.click(function(e) {
          e.preventDefault();
          const link = $('#link').val();
          if(isUrlValid(link)) {
            insertLink(link);
          } else {
            createFlash(rssFeedResponse, 'error', 'Url link is Invalid.');
          }
        });

        //Show links by connecting to the server.php and getting response
        function showLinks() {
          $.ajax({
            url: 'server.php',
            type: 'POST',
            data: {
              'method': 'show',
            },
            dataType: 'JSON',
          }).done(function(response) {
            createFlash(rssFeedResponse, response['flash_warning'], response['flash']);
            createFeed(rssFeed, response['data']);
            if(response['xml_data']) {
              createXML(table, response['xml_data']);
            } else {
              table.empty();
            }
          });
        }

        //Insert link by connecting to the server.php and getting response
        function insertLink(link) {
          $.ajax({
            url: 'server.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
              'method': 'insert',
              'link': link,
            },
          }).done(function(response) {
            createFlash(rssFeedResponse, response['flash_warning'], response['flash']);
            createFeed(rssFeed, response['data']);
            if(response['xml_data']) {
              createXML(table, response['xml_data']);
            } else {
              table.empty();
            }
          });
        }

        //Update link by connecting to the server.php and getting response
        function updateLink(oldlink, newlink) {
          $.ajax({
            url: 'server.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
              'method': 'update',
              'oldlink': oldlink,
              'newlink': newlink,
            },
          }).done(function(response) {
            createFlash(rssFeedResponse, response['flash_warning'], response['flash']);
            createFeed(rssFeed, response['data']);
            if(response['xml_data']) {
              createXML(table, response['xml_data']);
            } else {
              table.empty();
            }
          });
        }

        //Delete link by connecting to the server.php and getting response
        function deleteLink(link) {
          $.ajax({
            url: 'server.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
              'method': 'delete',
              'link': link,
            },
          }).done(function(response) {
            createFlash(rssFeedResponse, response['flash_warning'], response['flash']);
            createFeed(rssFeed, response['data']);
            if(response['xml_data']) {
              createXML(table, response['xml_data']);
            } else {
              table.empty();
            }
          });
        }

        //Get XML Content by link by connecting to the server.php and getting response
        function getContent(link) {
          $.ajax({
            url: 'server.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
              'method': 'xml',
              'link': link,
            },
          }).done(function(response) {
            createFlash(rssFeedResponse, response['flash_warning'], response['flash']);
            createFeed(rssFeed, response['data']);
            if(response['xml_data']) {
              createXML(table, response['xml_data']);
            } else {
              table.empty();
            }
          });
        }

        //Creates flash message to show, depending on what server.php/parameter value passed
        function createFlash(el, flashWarning, flash) {
          el.empty();
          el.append('<p class=' + flashWarning + '>' + flash + '</p>');
        }

        //Creates feed from data at server.php and formats them into links. Also creates event listeners to perform correct CRUD calls
        function createFeed(el, data) {
          el.empty();
          $(data).each(function(i) {
              const div = $('<div class="rss-link"></div>');
              const p = $('<p>' + data[i]['link'] +  '</p>');
              const update_input = $('<input type="text" id="update_link" name="update_link" placeholder="Update a link ..." />');
              const update_submit = $('<input type="submit" name="update_submit" value="Update Link"></input>');
              const getContentBtn = $('<button class="rss-link__get-content">Get Content</button>');
              const updateBtn = $('<button class="rss-link__update">Update</button>');
              const deleteBtn = $('<button class="rss-link__delete">Delete</button>');

              getContentBtn.click(function(e) {
                e.preventDefault();
                getContent(data[i]['link']);
              });

              updateBtn.click(function(e) {
                e.preventDefault();
                p.after(update_input);
                update_input.after(update_submit);
                p.hide();
                getContentBtn.hide();
                updateBtn.hide();
                deleteBtn.hide();
                update_submit.click(function(e) {
                  e.preventDefault();
                  const inputVal = update_input.val();
                  p.show();
                  getContentBtn.show();
                  updateBtn.show();
                  deleteBtn.show();
                  update_input.remove();
                  update_submit.remove();
                  if(isUrlValid(inputVal)) {
                    updateLink(p.text(), update_input.val());
                  } else {
                    createFlash(rssFeedResponse, 'error', 'Url link is Invalid.');
                  }
                });
              });

              deleteBtn.click(function(e) {
                e.preventDefault();
                deleteLink(data[i]['link']);
              });

              div.append(p);
              div.append(getContentBtn);
              div.append(updateBtn);
              div.append(deleteBtn);
              el.append(div);
          });
        }

        //Creates table for the data retrieved using server.php, formats XML data into table format
        function createXML(el, data) {
          el.empty();

          const headers = $('<tr><th>Title</th><th>Description</th><th>Link</th></tr>');
          const tbody = $('<tbody></tbody>');
          const table = $('<table></table>');

          tbody.append(headers);

          $(data).each(function(i) {
            const tr = $('<tr></tr>');

            if(data[i]['title'][0]) {
              tr.append('<td>' + data[i]['title'][0] + '</td>');
            }else {
              tr.append('<td>-</td>');
            }

            if(data[i]['description'][0]) {
              tr.append('<td>' + data[i]['description'][0] + '</td>');
            }else {
              tr.append('<td>-</td>');
            }

            if(data[i]['link'][0]) {
              tr.append('<td>' + data[i]['link'][0] + '</td>');
            }else {
              tr.append('<td>-</td>');
            }

            tbody.append(tr);

          });

          table.append(tbody);
          el.append(table);
        }

        //Validates URL on inputs
        function isUrlValid(url) {
          return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
      }
    });
 })(jQuery);