SRC=src
OUT=htdocs

.PHONY: all clean

all:
	cp -r $(SRC) $(OUT)

clean:
	rm -r $(OUT)

# vim: noexpandtab
