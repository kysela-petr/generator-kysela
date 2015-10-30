'use strict';

var generators = require('yeoman-generator');
var chalk = require('chalk');
var yosay = require('yosay');
var _ = require('underscore');
var _s = require('underscore.string');

module.exports = generators.Base.extend({
	initializing: function () {
		this.argument('appname', { type: String, required: false });
  

		this.appName = _s.camelize(_s.slugify(_s.humanize(this.appname)));
	    this.log(yosay('Creating new eSports web application ' + chalk.yellow.bold(this.appName) + '.'));
	    this.virtualHostUrl = this.appName.toLowerCase() + '.l';
	},

	prompting: function () {
		var done = this.async();

		var prompts = [
			{
				type: 'input',
				name: 'authorManager',
				message: 'Who are project managers?',
				default: ''
			},{
				type: 'input',
				name: 'authorDesigner',
				message: 'Who are designers?',
				default: ''
			},{
				type: 'input',
				name: 'authorBackend',
				message: 'Who are backend developers?',
				default: ''
			},{
				type: 'input',
				name: 'authorFrontend',
				message: 'Who are frontend coders?',
				default: ''
			},{
				type: 'confirm',
				name: 'useFTPDeployment',
				message: 'Would you like to install DG FTP Deployment support?',
				default: false
			},{
				when: function(props) { return props.useFTPDeployment; },
				type: 'input',
				name: 'ftpUser',
				message: 'FTP user:',
				default: ''
			},{
				when: function(props) { return props.useFTPDeployment; },
				type: 'input',
				name: 'ftpPassword',
				message: 'FTP password:',
				default: ''
			},{
				when: function(props) { return props.useFTPDeployment; },
				type: 'input',
				name: 'ftpHost',
				message: 'FTP host:',
				default: ''
			},{
				when: function(props) { return props.useFTPDeployment; },
				type: 'input',
				name: 'ftpDirectory',
				message: 'FTP root directory (must starts with / ):',
				default: '/'
			},{
				type: 'confirm',
				name: 'useAmazonS3',
				message: 'Would you like to install Grunt AWS S3 support?',
				default: false
			},{
				type: 'confirm',
				name: 'useUnderscoreJs',
				message: 'Would you like to install Underscore.js?',
				default: false
			},{
				type: 'input',
				name: 'databaseHost',
				message: 'Local database host:',
				default: 'localhost'
			},{
				type: 'input',
				name: 'databaseName',
				message: 'Local database name:',
				default: this.appName.toLowerCase()
			},{
				type: 'input',
				name: 'databaseUser',
				message: 'Local database user:',
				default: 'root'
			},{
				type: 'input',
				name: 'databasePassword',
				message: 'Local database password:',
				default: 'root'
			}
		];

		this.prompt(prompts, function (props) {
			this.props = props;
			// To access props later use this.props.someOption;

			done();
		}.bind(this));
	},

	writing: function () {

		var copies = [
			'clean.sh', 
			'.gitignore', 
			'app', 
			'bin', 
			'db', 
			'log', 
			'temp', 
			'tests', 
			'www', 
		];

		for (var i = copies.length - 1; i >= 0; i--) {
			var copy = copies[i];
			this.fs.copy(
				this.templatePath(copy),
				this.destinationPath(copy)
			);
		};
		
		var appDefaults = {
			appName: this.appName,
			manager: this.props.authorManager,
			designer: this.props.authorDesigner,
			backend: this.props.authorBackend,
			frontend: this.props.authorFrontend
		};

		var templates = [
			[
				'_/package.json',
				'package.json',
				_.extend(appDefaults, { 
					useCoffeeScript: this.props.useCoffeeScript,
					useAmazonS3: this.props.useAmazonS3 
				})
			],
			[
				'_/composer.json', 
				'composer.json',
				_.extend(appDefaults, {
					useFTPDeployment: this.props.useFTPDeployment
				})
			],
			[
				'_/bower.json', 
				'bower.json',
				_.extend(appDefaults, {
					useUnderscoreJs: this.props.useUnderscoreJs
				})
			],
			[
				'_/Gruntfile.js', 
				'Gruntfile.js',
				_.extend(appDefaults, {
					useUnderscoreJs: this.props.useUnderscoreJs
				})
			],
			[
				'_/www/humans.txt', 
				'www/humans.txt',
				appDefaults
			],
			[
				'_/readme.md', 
				'readme.md',
				_.extend(appDefaults, {
					virtualHostUrl: this.virtualHostUrl,
					pathToRoot: this.destinationRoot()
				})
			],
			[
				'_/httpd-vhosts.conf', 
				'httpd-vhosts.conf',
				{
					virtualHostUrl: this.virtualHostUrl,
					pathToRoot: this.destinationRoot()
				}
			],
			[
				'_/hosts', 
				'hosts',
				{
					virtualHostUrl: this.virtualHostUrl,
				}
			],
			[
				'_/app/config/config.local.neon', 
				'app/config/config.local.neon',
				{
					host: this.props.databaseHost,
					name: this.props.databaseName,
					user: this.props.databaseUser,
					password: this.props.databasePassword
				}
			]
		];
		
		for (var i = templates.length - 1; i >= 0; i--) {
			var template = templates[i];
			this.fs.copyTpl(
				this.templatePath(template[0]),
				this.destinationPath(template[1]),
				template[2]
			);
		};

		if ( this.props.useFTPDeployment ) {
			templates.push([
				'_/deploy.ini', 
				'deploy.ini',
				{
					appName: this.appName,
					host: this.ftpHost,
					dir: this.ftpDirectory,
					user: this.ftpUser,
					password: this.ftpPassword
				}
			]);
		}

	},

	install: function () {
		this.spawnCommandSync('composer', ['install']); //create-project', 'nette/sandbox', 'sb']);
		this.spawnCommandSync('npm', ['install']);
		this.spawnCommandSync('bower', ['install']);
	},

	end: function () {
		this.log(yosay('Done! Project ' + chalk.yellow.bold(this.appName) + ' is prepared.\n\nIn root folder are files (httpd-vhosts.conf and hosts), use them for your apache virtual host configuration ' + chalk.red.bold("Don't overwrite existing!") + '\n\nCreate webtemp in www folder and enable writing into /log /temp /www/webtemp /www/adminwww/webtemp.\n\nRun "grunt" or "grunt dist" and open virtual address in browser.'));
	}
});