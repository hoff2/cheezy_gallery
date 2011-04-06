Cheezy Gallery
==============

Super simple cheezy image gallery that makes its own thumbnails. I
worked this up quick-like for a simple site I was working on. Check
out the index.php file as an example of how to use it. Should be easy
to modify to your liking. Just stick each gallery's images in its own
subdirectory of some directory.

When cheezy_gallery.php is executed, a couple globals are set from the
query-string, if found there. $directory is the directory/gallery
selected and $feature is the filename of the image to feature big-like
at the top.

gallery() displays a table of the images in the given directory, with
the "feature" image at the top (or not). list_galleries() spits out a
list of links for each folder of images.

Since thumbnails are generated as-needed, if you upload a grip of
images the gallery will probably be really slow first time you go to
it and you might even have to refresh a couple times. After that
though, assuming you add just a few new files at a time, probably
won't be a problem.
