# HTML5 support for SilverStripe

## Maintainer Contact

* Hamish Friedlander <hamish@silverstripe.com>

## Requirements

* SilverStripe 3.1 Beta 3 or higher

## Summary

This module adds further HTML 5 support to SilverStripe.

Although SilverStripe supports using HTML 5 in templates out of the box, there are
some limitations in the use of HTML in the content managed through the CMS.

#### HTMLText & HTMLVarchar

This module allows SilverStripe to support HTML 5 in HTMLText and HTMLVarchar fields, by
providing a subclass of HTMLValue that uses the third party html5lib and causing the Injector
to use this subclass by default.

SilverStripe stores HTMLText and HTMLVarchar fields in models as strings, but
sometimes needs to convert these to DOM objects (for instance, to process shortcodes).

Default SilverStripe behavior is to do this with DOMDocument#loadHTML, but that method
throws an error when it encounters the new HTML5 element types. It also doesn't deal
with unclosed elements and invalid HTML in the manner prescribed by the HTML5 spec.

This module replaces the code that does this conversion with code that uses html5lib, which
supports HTML 5 as per the spec.
