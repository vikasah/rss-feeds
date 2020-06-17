const gulp = require('gulp');
const sass = require('gulp-sass');
const cssnano = require('gulp-cssnano');
const rename = require('gulp-rename');
const autoprefixer = require('gulp-autoprefixer');
const concat = require('gulp-concat');
const minify = require('gulp-minify');
const bs = require('browser-sync').create();

gulp.task('browser-sync', function() {
    bs.init({
        proxy: 'rss.test' //<-- Change to whatever your url is
    });
});
 
gulp.task('js', function () {    
    return gulp.src(['assets/js/vendor/*', 'assets/js/main.js'])
        .pipe(concat('main.js'))
        .pipe(minify())
        .pipe(gulp.dest('public/js'))
        .pipe(bs.reload({stream: true}));
});

gulp.task('sass', function(){
    return gulp.src('assets/scss/main.scss')
        .pipe(sass())
        .pipe(cssnano())
        .pipe(rename('style.min.css'))
        .pipe(autoprefixer({
            cascade: false
        }))
        .pipe(gulp.dest('public/css/'))
        .pipe(bs.reload({stream: true}))
});

gulp.task('serve', ['sass', 'js', 'browser-sync'], function() {
    gulp.watch(['assets/scss/includes/*', 'assets/scss/*'], ['sass']);
    gulp.watch('assets/js/main.js', ['js']);
    gulp.watch("*").on('change', bs.reload);
});

gulp.task('default', ['serve']);