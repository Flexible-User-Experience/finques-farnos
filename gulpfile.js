// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var jshint = require('gulp-jshint');
var less = require('gulp-less');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');

// Lint Task
gulp.task('lint', function() {
    return gulp.src('app/Resources/public/js/**/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// Compile Our Less
gulp.task('less', function() {
    return gulp.src('app/Resources/public/css/**/*.less')
        .pipe(less())
        .pipe(gulp.dest('web/css'));
});

// Concatenate & Minify JS
gulp.task('scripts', function() {
    return gulp.src('app/Resources/public/js/*.js')
        .pipe(concat('all.js'))
        .pipe(gulp.dest('web/js'))
        .pipe(rename('all.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/js'));
});

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('app/Resources/public/js/*.js', ['lint', 'scripts']);
    gulp.watch('web/less/*.less', ['less']);
});

// Default Task
gulp.task('default', ['lint', 'less', 'scripts']);
