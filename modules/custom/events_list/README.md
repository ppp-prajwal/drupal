# Events List

A module to display all events on a page from content type named
as event with fields name, description, place, date, image and star rating.

For a full description of the module, visit the
[project page](https://www.drupal.org/project/events_list).

Submit bug reports and feature suggestions, or track changes in the
[issue queue](https://www.drupal.org/project/issues/events_list).


## Table of contents

- Requirements
- Installation
- Configuration
- Functionality
- Approach
- Maintainers


## Requirements

This module requires no modules outside of Drupal core.


## Installation

Install as you would normally install a contributed Drupal module. For further
information, see
[Installing Drupal Modules](https://www.drupal.org/docs/extending-drupal/installing-drupal-modules).


## Configuration

- Add event to content type event in Drupal CMS.


## Functionality

- All published events are visible on /list-published-events.
- Each event is displayed in card layout with 3 card in a row and maximum number of rows as much as events are present in content type event.
- Image popout on hover event image.
- Ability to redirect to event detail page from event list page.
- Created module file /events/events.module to parse the #theme as a twig template and #items as a variables using hook_theme().
- Created template file /events/templates/list-published-events.html.twig and consumed variables using .operator on items.
- Created css file /events/css/list-published-events.css to style /list-published-events page.
- Created library events.list_published_events in events/events.libraries.yml to add css list-published-events.css and font-awesome library for star rating.
- Attached library events.list_published_events to EventsController using #attached keyword.
- Added style to contoller using list-published-events.css file.

## Approach

- Created and enabled a events_list module.
- Created content type event.
- Created routing file /events/events.routing.yml to route a Controller.
- Created controller /events/src/Controller/EventsController.php to extract all nodes of event content type in $data[] and returned #theme and #items($data[]).

## Maintainers

- Prajwal Bharambe - [plystudy](https://www.drupal.org/u/plystudy)
- Prajwal Bharambe - [ppp-prajwal]
(https://github.com/ppp-prajwal/drupal/)