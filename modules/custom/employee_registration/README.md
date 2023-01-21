## Employee Registration

- Create a form with the following data for a single registration:
  - Name of the employee (Free text, required)One plus (Yes/No, required)
  - Amount of kids (Number, required)
  - Amount of vegetarians (Number, required)
  - Email address (required)
- Create a small, untranslatable content type “Registration” that holds all the form fields. Create a new node on submit that is filled with the data from the submitted form. (You can use the employee name as title)
-Validate the form:
  - All required fields should be enforced
  - Only valid email addresses are allowed
  - Amount of vegetarians can not be higher than the total amount of people
  - An employee should not be able to register twice (with the same email address)
- Show the form on the path /registration
- Automatically capture the department from the URL, f.e. /registration/finance will automatically link the registration to the department “Finance” (This means that you can no longer register at /registration, without department)
- Add a department field to the content type and store the captured department on
newly created nodes (Department can be a textfield).
- Limit and validate the allowed departments by shipping the custom module with some default departments (f.e. Finance, IT, Consulting, ...) without hardcoding them.
- Create a block with a registration count that is shown on every page
- Make sure the registration count can be fetched by other modules
- Make sure that the registration count block is cached to prevent a load on every request whilst keeping the count up to date. A new registration should result in an updated block on the next request.
- Create a new role ”Department manager” and make sure it’s shipped with the custom module.
- Create a new permission “Manage event registrations”
- Grant department managers the custom permission
- Create a new form to add a department:
  - Show the form at /admin/config/add-department
  - Existing departments stay as is
  - The form should only be accessible for department managers
  - A department should have a machine readable name and a human readable name.
  - A new deploy will not reset the departments to the ones provided by the custom module, you can use a contributed module for this


## Table of contents

- Requirements
- Installation
- Configuration
- Functionality
- Approach
- Bugfixes
- Enhancements
- Maintainers


## Requirements

This module requires no modules outside of Drupal core.


## Installation

Install as you would normally install a contributed Drupal module. For further
information, see
[Installing Drupal Modules](https://www.drupal.org/docs/extending-drupal/installing-drupal-modules).


## Configuration


## Functionality

- Emplyees can register for an upcoming events.


## Approach

- Create and enable a employee_registration module.
- Create a form with fields Employee Name, One Plus, Amount of Kids, Amount of Vegeterians, Email Address.
- Validate the form using function validateForm in \Drupal\employee_registration\Form\RegistrationForm.


## Bugfixes


## Enhancements


## Maintainers

- Prajwal Bharambe - [ppp-prajwal]
(https://github.com/ppp-prajwal/drupal/)