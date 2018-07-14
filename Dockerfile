FROM tobilg/predictionio

RUN apt-get update -y && apt-get install -y vim

EXPOSE 7070 8000 9000