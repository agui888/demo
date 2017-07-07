#version：0.1
#auth:cool
#date:2015.5.27

CC = gcc

TARGET = threadpool
LIBSO_NAME = libngx_threadpool.so
SRC_PATH = .
SRCS = $(wildcard $(SRC_PATH)/*.c)
SRC_TMP = $(notdir $(SRCS))
OBJS = $(SRC_TMP:%.c = %.o)
ARGV = -g
LIBSO =   -lpthread
all:$(OBJS)
	$(CC) $(ARGV) $(OBJS) $(LIBSO) -o $(TARGET)
#	$(CC) -fPIC -shared  $(OBJS) $(LIBSO) -o $(LIBSO_NAME)
	
%.o:%.c
	$(CC) -c -o $@ $<

.PHONY: clean
clean:
	rm $(TARGET)
	rm $(LIBSO_NAME)
	rm *.o -f
