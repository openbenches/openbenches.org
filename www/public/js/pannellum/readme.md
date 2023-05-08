# Pannellum 2.5.6

## About
Pannellum is a lightweight, free, and open source panorama viewer for the web. Built using HTML5, CSS3, JavaScript, and WebGL, it is plug-in free. It can be deployed easily as a single file, just 21kB gzipped, and then embedded into pages as an `<iframe>`. A configuration utility is included to generate the required code for embedding. An API is included for more advanced integrations.

## Browser Compatibility
Since Pannellum is built with web standards, it requires a modern browser to function.

#### Full support (with appropriate graphics drivers):
* Firefox 23+
* Chrome 24+
* Safari 8+
* Internet Explorer 11+
* Edge

The support list is based on feature support. As only recent browsers are tested, there may be regressions in older browsers.

#### Not officially supported:
Mobile / app frameworks are not officially supported. They may work, but they're not tested and are not the targeted platform.

## Translations
All user-facing strings can be changed using the `strings` configuration parameter. There exists a [third-party respository of user-contributed translations](https://github.com/DanielBiegler/pannellum-translation) that can be used with this configuration option.

## Seeking support
If you wish to ask a question or report a bug, please open an issue at [github.com/mpetroff/pannellum](https://github.com/mpetroff/pannellum). See the _Contributing_ section below for more details.

## Contributing
Development takes place at [github.com/mpetroff/pannellum](https://github.com/mpetroff/pannellum). Issues should be opened to report bugs or suggest improvements (or ask questions), and pull requests are welcome. When reporting a bug, please try to include a minimum reproducible example (or at least some sort of example). When proposing changes, please try to match the existing code style, e.g., four space indentation and [JSHint](https://jshint.com/) validation. If your pull request adds an additional configuration parameter, please document it in `doc/json-config-parameters.md`. Pull requests should preferably be created from [feature branches](https://www.atlassian.com/git/tutorials/comparing-workflows/feature-branch-workflow).

## License
Pannellum is distributed under the MIT License. For more information, read the file `COPYING` or peruse the license [online](https://github.com/mpetroff/pannellum/blob/master/COPYING).

In the past, parts of Pannellum were based on [three.js](https://github.com/mrdoob/three.js) r40, which is licensed under the [MIT License](https://github.com/mrdoob/three.js/blob/44a8652c37e576d51a7edd97b0f99f00784c3db7/LICENSE).

The panoramic image provided with the examples is licensed under the [Creative Commons Attribution-ShareAlike 3.0 Unported License](http://creativecommons.org/licenses/by-sa/3.0/).

## Credits

* [Matthew Petroff](http://mpetroff.net/), Original Author
* [three.js](https://github.com/mrdoob/three.js) r40, Former Underlying Framework

If used as part of academic research, please cite:

> Petroff, Matthew A. "Pannellum: a lightweight web-based panorama viewer." _Journal of Open Source Software_ 4, no. 40 (2019): 1628. [doi:10.21105/joss.01628](https://doi.org/10.21105/joss.01628)
