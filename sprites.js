var fs = require('fs');
var postcss = require('postcss');
var sprites = require('postcss-sprites');

var css = fs.readFileSync('./wp/css/total.css', 'utf8');
var opts = {
    stylesheetPath: './wp/dist',
    spritePath: './wp/dist/images/'
};

postcss([sprites(opts)])
    .process(css, {
        from: './wp/css/total.css',
        to: './wp/dist/style.css'
    })
    .then(function(result) {
        fs.writeFileSync('./wp/dist/style.css', result.css);
    });