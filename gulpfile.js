var gulp = require('gulp');

gulp.task('default', function(){
  return gulp.src([
      'node_modules/semantic-ui-css/**.min.css',
      'node_modules/chart.js/dist/*.min.js'
  ]).pipe(gulp.dest('web/assets'))
});
