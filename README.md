

<h1 align="center">
   Activity Service
</h1>


## 🚀 Environment Setup

### 🐳 Needed tools

1. [Install Docker](https://www.docker.com/get-started)
2. Clone this project: `git clone https://github.com/gerardus1995/HolaLuzTechnicalTest`
3. Move to the project folder: `cd activity_service`

### 🛠️ Environment configuration

1. Create a local environment file (`cp .env .env.local`) 

### 🔥 Application execution

1. Start the project with docker `make start`
2. Now you can access http://localhost:81/ to know it all starts correctly
3. Enter the project command line `docker exec -it activities_php bash`
4. Execute the following command `bin/console app:detect:suspicious-readings 2016-readings.xml` for XML
4. Execute the following command `bin/console app:detect:suspicious-readings 2016-readings.csv` for CSV
`