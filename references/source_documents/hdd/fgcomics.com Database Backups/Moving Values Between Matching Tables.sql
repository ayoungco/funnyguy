-- INSERT INTO Comics (isFirstPage, ComicWidth, ComicHeight)
-- SELECT isFirstPage, ComicWidth, ComicHeight FROM `The Funny Guy Comix`;

SET SQL_SAFE_UPDATES = 0;
UPDATE Comics, `The Funny Guy Comix` fg 
SET Comics.isFirstPage = fg.isFirstPage, 
	Comics.ComicWidth = fg.ComicWidth,
    Comics.ComicHeight = fg.ComicHeight
WHERE Comics.ComicID = fg.id;
SET SQL_SAFE_UPDATES = 1;