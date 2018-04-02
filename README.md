# HTML5 support for SilverStripe

[![Build Status](https://travis-ci.org/silverstripe/silverstripe-html5.svg?branch=master)](https://travis-ci.org/silverstripe/silverstripe-html5)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/silverstripe/silverstripe-html5/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/silverstripe/silverstripe-html5/?branch=master)
[![codecov](https://codecov.io/gh/silverstripe/silverstripe-html5/branch/master/graph/badge.svg)](https://codecov.io/gh/silverstripe/silverstripe-html5)

## Requirements

* SilverStripe 4.0 or higher

For a SilverStripe 3.x compatible version of this module, please see the [1.0 branch, or 1.x release line](https://github.com/silverstripe/silverstripe-html5/tree/1.0#readme).

## Summary

This module adds further HTML 5 support to SilverStripe.

Although SilverStripe supports using HTML 5 in templates out of the box, there are
some limitations in the use of HTML in the content managed through the CMS.

#### HTMLText & HTMLVarchar

This module allows SilverStripe to support HTML 5 in HTMLText and HTMLVarchar fields, by
providing a subclass of HTMLValue that uses the third party [html5lib](https://github.com/html5lib/html5lib-php)
and causing the Injector to use this subclass by default.

SilverStripe stores HTMLText and HTMLVarchar fields in models as strings, but
sometimes needs to convert these to DOM objects (for instance, to process shortcodes).

Default SilverStripe behavior is to do this with DOMDocument#loadHTML, but that method
throws an error when it encounters the new HTML5 element types. It also doesn't deal
with unclosed elements and invalid HTML in the manner prescribed by the HTML5 spec.

This module replaces the code that does this conversion with code that uses [html5lib](https://github.com/html5lib/html5lib-php),
which supports HTML 5 as per the spec.
