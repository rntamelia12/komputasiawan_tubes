pipeline {
    agent any
    environment {
        DOCKER_COMPOSE_FILE = 'docker-compose.yml'
        DOCKER_PROJECT_NAME = 'komputasiawan_tubes'
        DOCKER_IMAGE = 'vazcodacosta/kostubes:v1.0'
        GIT_REPO = 'https://github.com/rntamelia12/komputasiawan_tubes'
        GIT_BRANCH = 'main'
    }

    stages {
        stage('Clone Repository') {
            steps {
                echo "Cloning repository..."
                git branch: "${GIT_BRANCH}", url: "${GIT_REPO}"
            }
        }

        stage('Build Docker Image') {
            steps {
                echo "Building Docker image..."
                script {
                    docker.build("${DOCKER_IMAGE}", ".")
                }
            }
        }

        // stage('Push Docker Image') {
        //     steps {
        //         echo "Pushing Docker image to Docker Hub..."
        //         script {
        //             sh '''
        //             # Login to Docker Hub (requires credentials configured in Jenkins)
        //             echo $DOCKER_HUB_PASSWORD | docker login -u $DOCKER_HUB_USERNAME --password-stdin

        //             # Push the image
        //             docker push ${DOCKER_IMAGE}
        //             '''
        //         }
        //     }
        // }
    }

    post {
        success {
            echo "Pipeline executed successfully! Image has been pushed to Docker Hub."
        }
        failure {
            echo "Pipeline failed. Please check the logs."
        }
    }
}
