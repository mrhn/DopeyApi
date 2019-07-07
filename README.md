# DopeyApi

Show case of an simple api, built as a restaurant booking system implementation, thought out as only an API.

Focus is on code quality oriented aspects with the following approaches. This is not a necesity and i know it can't always be done, is mainly done as this is how i think it should be done in a perfect world.

### Code quality

- Testing (mainly feature testing)
- php-cs-fixer for automatic linting
- Larastan for a more type strict and less error prone code
- CI running on circleci

### Style comments
I'm not a big fan of including php docs and comments all over the place. If it does not tell another story, than the Php language can do with typed parameters and return types, i do not include it.

### How to run it
It's pretty thoroughly tested, this clearly states how i have intended the api to work.

### Whats missing?

- Patch and Delete operations for reservations
- Pagination and proper searching for the external api's
- More aligned error handling
- User repository/service, there is some janky user logic copy pasted to many places
