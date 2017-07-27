# Implementing Domain Driven Design in PHP using Laravel

Teamo is a collaboration service for small teams working on their projects. It's a place to keep discussions, tasks and events.

In Teamo every user can create unlimited number of projects, and join any projects of other users by invitation. So there is no main account of the team, there are just users and projects. User can have projects with different teams not related to each other.

This repository is an example of how such Laravel application may look. Application consists of two bounded contexts: User and Project.

In User bounded context there are things like registration, login, account settings and preferences.

Project bounded context is where everything happens, every User is a Team Member of a Project here.

These two bounded contexts are a part of the same application, however they can be easily separated since there is no direct connection between them except for few Domain Event Subscribers. They share the same database, but they don't share tables in it.

When working on new application, you usually don't need to distribute your system right away, but you can/should write a code that will be easy to distribute later.

### What this code is

This code is an example of how your DDD application with Hexagonal architecture may look. Please visit /app directory.

### What this code is not

This is definitely not a good example of frond-end development skills. Actually I just copied HTML, CSS and JavaScript from old project without trying to fix anything. So please don't use it as a reference on organising your resources.
