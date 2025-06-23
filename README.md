<div style="padding: 10px; display: flex; justify-content: center">
    <img src="storage/public/logo.svg" alt="eskuelMyadmin"/>
</div>

<h2> contents </h2>
<ul>
    <li>
        <a href="#about-project">📃 About project</a>
    </li>
    <li>
        <a href="#setup-with-docker-apache">⚙️ Create apache image with docker</a>
    </li>
</ul>

<h2 id="about-project">📃 About project</h2>

EksuelMyAdmin is a tool created to manage databases locally. 
It allows users to add databases on their computer and log in to them easily. 
The project supports the full SQL language, so users can write SQL commands and see the results directly in the app.
The main feature of the project is <strong>Eksuel</strong> – a Polish version of SQL. The program uses a special translation layer that changes Polish commands into standard SQL queries. This helps people who speak Polish work with databases without needing to know English SQL syntax.

<h2>⚙️ Setup with docker</h2>
<h3 id="setup-with-docker-apache">Apache:</h3>
Every files used to build apache image is in /docker-setup/apache/{version} directory

#### versions
<ul>
    <li>
        1.0 - apache2 and php8.3
    </li>
</ul>

#### 📁 directory contents:
<ul>
    <li>Dockerfile - This is a configuration file used by Docker to build a container image. It contains step-by-step instructions on what software and settings to include in the image</li>
    <li>php.ini - This is the main PHP configuration file. It defines the operating parameters of the PHP interpreter,</li>
    <li>000-default.conf - This is the Apache HTTP Server (web server) configuration file</li>
</ul>

#### ✅ requirements

- **Docker** → [Download here](https://www.docker.com)
- **Optional:** `docker-compose`, if you are not using `docker compose`

<h4>Example docker-compose.yml:</h4>

```
  apache:
    build:
      context: ./docker-setup/apache/1.0
      dockerfile: Dockerfile
      args:
        WWWGROUP: 1000
        WWWUSER: 1000
    container_name: ESKUEL_apache
    restart: always
    ports:
      - "80:80"
      - "5173:5173"
    volumes:
      - ./:/var/www/html
    depends_on:
      - db
    healthcheck:
      test: ["CMD-SHELL", "curl -f http://localhost || exit 1"]
      retries: 3
      timeout: 5s

```