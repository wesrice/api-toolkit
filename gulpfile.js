const gulp = require('gulp');
const util = require('gulp-util');
const yargs = require('yargs').argv
const swagger = require('gulp-swagger');

const namespace = yargs.namespace || 'craft';
const dirname = __dirname;

const config = {
  src: yargs.src || `${dirname}/oai-schema/v2/${namespace}/index.yaml`,
  watch: yargs.watch || `${dirname}/oai-schema/v2/${namespace}/**/*.yaml`,
  filename: yargs.filename || `${namespace}.json`,
  dest: yargs.dest || `${dirname}/src/resources/oai-schema/v2`
};
 
gulp.task('oai:2.0:compile', () => {
  return gulp.src(config.src)
    .on('end', () => util.log(`Reading from source: ${config.src}`))
    .pipe(swagger(config.filename))
    .on('error', (error) => util.log(error.toString()))
    .pipe(gulp.dest(config.dest))
    .on('end', () => util.log(`Writing to dest: ${config.dest}/${config.filename}`));
});

gulp.task('oai:2.0:watch', function () {
  gulp.watch(config.watch, ['oai:2.0:compile']);
});
 
gulp.task('default', ['oai:2.0:watch']);
