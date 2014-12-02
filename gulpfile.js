var gulp      = require('gulp'),
    less      = require('gulp-less'),
    concat    = require('gulp-concat'),
    minifyCSS = require('gulp-minify-css'),
    rename    = require('gulp-rename');

gulp.task('less', function () {
    gulp.src('less/**/*.less')
        .pipe(less())
        .pipe(gulp.dest('css'))
        .pipe(concat('style.css'))
        .pipe(gulp.dest('./'))
        .pipe(minifyCSS())
        .pipe(rename('style.min.css'))
        .pipe(gulp.dest('./'));
});

gulp.task('default', ['less']);
