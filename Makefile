# Define variables for Docker commands to increase readability
IMAGE_NAME=phpunit-test
CONTAINER_NAME=phpunit-test-container

# Command to build the Docker image
build:
	docker build -t $(IMAGE_NAME) .

# Command to run the Docker container
run:
	docker run --rm --name $(CONTAINER_NAME) $(IMAGE_NAME)

# Command to build and then run
build-run: build run