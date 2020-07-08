# BizWHois Project

Hi and welcome to my project.

***

**Table of Contents**

* [Presentation](#presentation)
* [Prerequisites](#prerequisites)
* [About your implementation](#about-your-implementation)
* [Environment](#your-dev-environment)
* [The task](#the-task)

***

<a id="presentation"></a>
## Presentation

This challenge is designed to test your full stack abilities (PHP/JS).

You should approach the task as you would any other piece of work in a typical day.
Think about the tools and libraries you might use to make your life easier (frameworks, libraries, etc.).

Think about your markup and CSS in terms of re-usability and maintainability across a larger scale product.

You should be able to produce the work with a high quality finish in an acceptable amount of time.

<a id="prerequisites"></a>
## Prerequisites

If you're using Mac OS, you will need:
* You will need to install [Docker](https://store.docker.com/editions/community/docker-ce-desktop-mac)

If you're using windows, you will need:
* [Docker toolbox](https://docs.docker.com/toolbox/toolbox_install_windows/) to use Docker and creating containers under windows
* put this whole folder inside `C/Users/Public` (to avoid having volume mounting issues)

<a id="about-your-implementation"></a>
## About your implementation

* If you have any notes to add to your test, please add them in the [Your comments](#your-comments) section below.
* Send a zip file with your completed entry to [marius.cucuruz@gmail.com](mailto:marius.cucuruz@gmail.com).

<a id="your-dev-environment"></a>
## Your dev environment

All you have to do, is run `docker-compose up -d (--build)` and it will download all the relevant modules for each of the stacks and then spin up 2 containers:
* `api.BizWHois.local` (for back end work, accessible on [http://localhost:8000/](http://localhost:8000/))
* `app.BizWHois.local` (for front end work, accessible on [http://localhost:8080/](http://localhost:8080/))

> **NOTE: If using Windows & Docker toolbox, run `docker-machine ip` to get the IP of your docker containers, then use this IP instead of `localhost` followed by the same ports as described above**
*(e.g. IP is `192.168.99.100`, API docs container will be accessible on [http://192.168.99.100:8001](http://192.168.99.100:8001))*

<a id="the-task"></a>
## The task

We need to build a very intuitive app to allow searching records against the Companies House DB.

For this test you will need to implement this API, and build a simple frontend to search and list results.
* start the project by running `docker-compose up --build -d && docker-compose logs -f` from the root of the project which will serve the application on `http://localhost:3000/`;
* I've opted for `docker-compose` as a personal preference;

***
