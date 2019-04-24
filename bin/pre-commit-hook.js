#!/usr/bin/env node

/**
 * External dependencies
 */
const execSync = require( 'child_process' ).execSync;
const chalk = require( 'chalk' );

console.log(
	'\nBy contributing to this project, you license the materials you contribute ' +
		'under the GNU General Public License v2 (or later). All materials must have ' +
		'GPLv2 compatible licenses — see .github/CONTRIBUTING.md for details.\n\n'
);

/**
 * Parses the output of a git diff command into javascript file paths.
 *
 * @param   {String} command Command to run. Expects output like `git diff --name-only […]`
 * @returns {Array}          Paths output from git command
 */
function parseGitDiffToPathArray( command ) {
	return execSync( command )
		.toString()
		.split( '\n' )
		.map( name => name.trim() )
		.filter(
			name => name.endsWith( '.js' ) || name.endsWith( '.jsx' ) || name.endsWith( '.scss' ) || name.endsWith( '.php' )
		);
}

const dirtyFiles = new Set( parseGitDiffToPathArray( 'git diff --name-only --diff-filter=ACM' ) );

const files = parseGitDiffToPathArray( 'git diff --cached --name-only --diff-filter=ACM' );

// PHP Lint.
let phpLintErrors = '';
let phpFiles = '';

files.forEach( file => {
	let fileHasPhpLintErrors = false;
	if ( ! file.endsWith( '.php' ) ) {
		return;
	}

	try {
		// Check if PHP_CodeSniffer is installed.
		execSync( `./vendor/bin/phpcbf -h` );
		execSync( `./vendor/bin/phpcs -h` );
	} catch( e ) {
		console.log(
			'PHP_CodeSniffer is not installed. ' +
			'Please, run `composer install` ' +
			'and run the command again.' );
		process.exit( 1 );
	}

	try {
		// Apply auto-fix.
		execSync( `./vendor/bin/phpcbf --standard=phpcs.xml.dist ${ file }` );
	} catch( e ) {
		try {
			// Check if there are still errors.
			execSync( `./vendor/bin/phpcs --standard=phpcs.xml.dist ${ file }` );
		} catch( err ) {
			fileHasPhpLintErrors = true;
			phpLintErrors = err.stdout.toString( 'utf8' );
		}
	}

	if ( ! fileHasPhpLintErrors ) {
		execSync( `git add ${ file }` );
	}

	phpFiles += ' ' + file;
} );

if ( phpLintErrors ) {
	console.log(
		chalk.red( 'COMMIT ABORTED:' ),
		'The PHP linter reported some errors. ' +
			'Fix all PHP syntax errors found, ' +
			'and repeat the commit command.'
	);
	console.log( phpLintErrors );
	process.exit( 1 );
}

// Check PHP files with PHPCS.
if ( phpFiles ) {
	console.log( 'Running PHP_CodeSniffer...' );
	try {
		execSync( `./vendor/bin/phpcs --encoding=utf-8 -n -p ${ phpFiles }` );
	} catch( err ) {
		console.log( err.stdout.toString( 'utf8' ) );

		console.log(
			chalk.red( 'COMMIT ABORTED:' ),
			'Fix all PHP_CodeSniffer errors before continue. \n' +
				'Run the following command to automatic fix some of the errors: \n' +
				`./vendor/bin/phpcbf ${ phpFiles }`
		);

		process.exit( 1 );
	}
}
