# HTML5 support for SilverStripe

__NOTE__: This module is no longer commercially supported in Silverstripe CMS 5 and it does not provide a CMS5-compatible version.
Since Silverstripe CMS 5 it's a part of core functionality. 

[![CI](https://github.com/silverstripe/silverstripe-html5/actions/workflows/ci.yml/badge.svg)](https://github.com/silverstripe/silverstripe-html5/actions/workflows/ci.yml)

## Requirements

* Silverstripe 4.0 or higher

For a Silverstripe 3.x compatible version of this module, please see the [1.0 branch, or 1.x release line](https://github.com/silverstripe/silverstripe-html5/tree/1.0#readme).

This module is not compatible or required for Silverstripe CMS 5. HTML5 is natively used by Silverstripe CMS 5.

## Summary

This module adds further HTML 5 support to SilverStripe.

Although Silverstripe supports using HTML 5 in templates out of the box, there are
some limitations in the use of HTML in the content managed through the CMS.

#### HTMLText & HTMLVarchar

This module allows Silverstripe to support HTML 5 in HTMLText and HTMLVarchar fields, by
providing a subclass of HTMLValue that uses the third party [html5lib](https://github.com/html5lib/html5lib-php)
and causing the Injector to use this subclass by default.

Silverstripe stores HTMLText and HTMLVarchar fields in models as strings, but
sometimes needs to convert these to DOM objects (for instance, to process shortcodes).

Default Silverstripe behavior is to do this with DOMDocument#loadHTML, but that method
throws an error when it encounters the new HTML5 element types. It also doesn't deal
with unclosed elements and invalid HTML in the manner prescribed by the HTML5 spec.

This module replaces the code that does this conversion with code that uses [html5lib](https://github.com/html5lib/html5lib-php),
which supports HTML 5 as per the spec.
