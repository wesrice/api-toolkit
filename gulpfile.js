const gulp = require('gulp');
const util = require('gulp-util');
const yargs = require('yargs').argv
const swagger = require('gulp-swagger');

const config = {
  src: yargs.src || __dirname + '/specs/2.0/craft.swagger.yaml',
  filename: yargs.filename || 'craft.schema.json',
  dest: yargs.dest || __dirname + '/src/resources/apitoolkit/specs/2.0'
};
 
gulp.task('swagger:compile', () => {
  return gulp.src(config.src)
    .on('end', () => util.log(`Reading from source: ${config.src}`))
    .pipe(swagger(config.filename))
    .pipe(gulp.dest(config.dest))
    .on('end', () => util.log(`Writing to dest: ${config.dest}/${config.filename}`));
});
 
gulp.task('default', ['swagger:compile']);
