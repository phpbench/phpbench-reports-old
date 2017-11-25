var gulp = require('gulp');

gulp.task('default', function(){
  return gulp.src('node_modules/semantic-ui-css/**.css')
    .pipe(gulp.dest('web/assets'))
});
