// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var jshint = require('gulp-jshint');
var less = require('gulp-less');
var concat = require('gulp-concat');
var minifycss = require('gulp-minify-css');
var usemin = require('gulp-usemin');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');

// Lint Task
gulp.task('lint', function() {
    return gulp.src('app/Resources/public/js/**/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// Compile & Minify Less in production environment
gulp.task('less', function() {
    return gulp.src(['bower_components/bootstrap/less/bootstrap.less', 'app/Resources/public/css/**/*.less'])
        .pipe(concat('main.css'))
        .pipe(less())
        .pipe(minifycss())
        .pipe(gulp.dest('web/css'));
});

// Compile & Minify Less in development environment
gulp.task('devless', function() {
    return gulp.src(['bower_components/bootstrap/less/bootstrap.less', 'app/Resources/public/css/**/*.less'])
        .pipe(concat('main.css'))
        .pipe(less())
        .pipe(gulp.dest('web/css'));
});

// Concatenate & Minify JS in production environment
gulp.task('scripts', function() {
    return gulp.src(['bower_components/jquery/dist/jquery.js', 'bower_components/lodash/dist/lodash.js', 'bower_components/numeral/numeral.js', 'bower_components/numeral/languages/es.js', 'bower_components/modernizr/modernizr.js', 'bower_components/bootstrap/dist/js/bootstrap.js', 'bower_components/angular/angular.js', 'bower_components/angular-resource/angular-resource.js', 'bower_components/angular-cookies/angular-cookies.js', 'bower_components/angular-sanitize/angular-sanitize.js', 'bower_components/angular-route/angular-route.js', 'bower_components/angular-touch/angular-touch.js', 'bower_components/angular-google-maps/dist/angular-google-maps.js'])
        .pipe(concat('main.js'))
        .pipe(gulp.dest('web/js'))
        .pipe(rename('main.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/js'));
});

// Concatenate & Minify JS in development environment
gulp.task('devjs', function() {
    return gulp.src(['bower_components/jquery/dist/jquery.js', 'bower_components/lodash/dist/lodash.js', 'bower_components/numeral/numeral.js', 'bower_components/numeral/languages/es.js', 'bower_components/modernizr/modernizr.js', 'bower_components/bootstrap/dist/js/bootstrap.js', 'bower_components/angular/angular.js', 'bower_components/angular-resource/angular-resource.js', 'bower_components/angular-cookies/angular-cookies.js', 'bower_components/angular-sanitize/angular-sanitize.js', 'bower_components/angular-route/angular-route.js', 'bower_components/angular-touch/angular-touch.js', 'bower_components/angular-google-maps/dist/angular-google-maps.js'])
        .pipe(gulp.dest('web/js'));
});

// Concatenate & Minify JS in production environment
gulp.task('myjs', function() {
    return gulp.src('app/Resources/public/js/**/*.js')
        .pipe(concat('my.js'))
        .pipe(gulp.dest('web/js'))
        .pipe(rename('my.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/js'));
});

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('app/Resources/public/js/**/*.js', ['lint', 'myjs']);
    gulp.watch('app/Resources/public/css/**/*.less', ['less']);
});

// Default Task
gulp.task('default', ['lint', 'less', 'scripts', 'myjs']);
