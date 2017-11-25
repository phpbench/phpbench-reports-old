var gulp = require('gulp');

gulp.task('default', function(){
  return gulp.src([
      'node_modules/semantic-ui-css/**.css',
      'node_modules/chart.js/dist/*.js'
  ]).pipe(gulp.dest('web/assets'))
});
