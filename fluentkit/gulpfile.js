var gulp = require('gulp');
var gulp_autoprefixer = require('gulp-autoprefixer');
var gulp_imagemin = require('gulp-imagemin');
var gulp_minify_css = require('gulp-minify-css');
var gulp_rename = require('gulp-rename');
var gulp_sass = require('gulp-ruby-sass');
var gulp_uglify = require('gulp-uglify');
var gulp_util = require('gulp-util');
var gulp_bower = require('gulp-bower-files');

gulp.task('default',['bower'], function() {
  // place code for your default task here
});


gulp.task('bower', function(cb){
    
    //jquery
    gulp.src('bower_components/jquery/dist/jquery.js')
    .pipe(gulp_rename('jquery.js'))
    .pipe(gulp.dest('../content/vendor/jquery/'));
    //jquery min
    gulp.src('bower_components/jquery/dist/jquery.min.js')
    .pipe(gulp_rename('jquery.min.js'))
    .pipe(gulp.dest('../content/vendor/jquery/'));
    
    //jquery-ui
    gulp.src('bower_components/jquery-ui/ui/jquery-ui.js')
    .pipe(gulp_rename('jquery-ui.js'))
    .pipe(gulp.dest('../content/vendor/jquery-ui/'));
    //jquery-ui min
    gulp.src('bower_components/jquery-ui/ui/minified/jquery-ui.min.js')
    .pipe(gulp_rename('jquery-ui.min.js'))
    .pipe(gulp.dest('../content/vendor/jquery-ui/'));
    
    //bootstrapjs
    gulp.src('bower_components/bootstrap/dist/js/bootstrap.js')
    .pipe(gulp_rename('bootstrap.js'))
    .pipe(gulp.dest('../content/vendor/bootstrap/js/'));
    //bootstrapjs min
    gulp.src('bower_components/bootstrap/dist/js/bootstrap.min.js')
    .pipe(gulp_rename('bootstrap.min.js'))
    .pipe(gulp.dest('../content/vendor/bootstrap/js/'));
    //bootstrapfonts
    gulp.src('bower_components/bootstrap/dist/fonts/*')
    .pipe(gulp.dest('../content/vendor/bootstrap/fonts'));
    //bootstrapcss
    gulp.src('bower_components/bootstrap/dist/css/bootstrap.css')
    .pipe(gulp_rename('bootstrap.css'))
    .pipe(gulp.dest('../content/vendor/bootstrap/css'));
    //bootstrapcss min
    gulp.src('bower_components/bootstrap/dist/css/bootstrap.min.css')
    .pipe(gulp_rename('bootstrap.min.css'))
    .pipe(gulp.dest('../content/vendor/bootstrap/css'));
    //bootstrapthemecss
    gulp.src('bower_components/bootstrap/dist/css/bootstrap-theme.css')
    .pipe(gulp_rename('bootstrap-theme.css'))
    .pipe(gulp.dest('../content/vendor/bootstrap/css'));
    //bootstrapthemecss min
    gulp.src('bower_components/bootstrap/dist/css/bootstrap-theme.min.css')
    .pipe(gulp_rename('bootstrap-theme.min.css'))
    .pipe(gulp.dest('../content/vendor/bootstrap/css'));
    
    //angularjs
    gulp.src('bower_components/angular/angular.js')
    .pipe(gulp_rename('angular.js'))
    .pipe(gulp.dest('../content/vendor/angular/'));
    //angularjs min
    gulp.src('bower_components/angular/angular.min.js')
    .pipe(gulp_rename('angular.min.js'))
    .pipe(gulp.dest('../content/vendor/angular/'));
    
    //respondjs
    gulp.src('bower_components/respond/dest/respond.src.js')
    .pipe(gulp_rename('respond.js'))
    .pipe(gulp.dest('../content/vendor/respond/'));
    //respondjs min
    gulp.src('bower_components/respond/dest/respond.min.js')
    .pipe(gulp_rename('respond.min.js'))
    .pipe(gulp.dest('../content/vendor/respond/'));
    
    //html5shiv
    gulp.src('bower_components/html5shiv/dist/html5shiv.js')
    .pipe(gulp_rename('html5shiv.js'))
    .pipe(gulp.dest('../content/vendor/html5shiv/'));
    //html5shivmin
    gulp.src('bower_components/html5shiv/dist/html5shiv.min.js')
    .pipe(gulp_rename('html5shiv.min.js'))
    .pipe(gulp.dest('../content/vendor/html5shiv/'));
    //html5shiv-printshiv
    gulp.src('bower_components/html5shiv/dist/html5shiv-printshiv.js')
    .pipe(gulp_rename('html5shiv-printshiv.js'))
    .pipe(gulp.dest('../content/vendor/html5shiv/'));
    //html5shiv-printshivmin
    gulp.src('bower_components/html5shiv/dist/html5shiv-printshiv.min.js')
    .pipe(gulp_rename('html5shiv-printshiv.min.js'))
    .pipe(gulp.dest('../content/vendor/html5shiv/'));
    
    //return the callback to ensure task completes first
    cb();
    
});