# fixbetranslationsorting

## What does it do?

It fixes the sorting of translated elements in the TYPO3 backend page module, currently only tested with connected mode.
The translated content elements sometimes appear in the wrong order, even if the ordering in the frontend is correct. 
In the backend, only the sorting field is used, without using the connection to the content elements of the original language.
If those sorting values are incorrect, for whatever reason, the problem appears.
https://forge.typo3.org/issues/81328

## Why use it?

Editors find it confusing, if the sorting of translated elements in the Backend does match neither the sorting 
of elements in default language nor the sorting in the frontend.

## How does it work?

It adds a small XClass to the QueryBuilder to allow for additional sorting options and uses an event to adapt the
sorting of translated content elements to the sorting of their connected default language content elements.
