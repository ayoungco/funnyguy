# FGComics theme archive

Static mockups of FGComics web designs that never fully shipped, rebuilt from the original Photoshop comps.
Open `index.html`: a dropdown switches themes, "Random" picks another, the choice persists (URL hash + localStorage).

- `base.css` shared 700px layout; each `themes/<id>/theme.css` sets slice images and nav-pill position (url() paths are relative to `base.css`)
- `themes.js` registry (id, name, era, source PSD). Add a theme = add a folder + one line here.
- Slices are cut from `output/psd_layers/<psd>/composite.png` (see `scripts/psd_layers.py`); sidebar/ad mockup layers were hidden before slicing the blue and green headers.

| Theme | Source PSD | Era |
|---|---|---|
| new-concept-sky | New Concept.psd | Nov 2005 |
| new-concept-blue | New Concept 2.psd | Nov 2005 |
| new-concept-green | New Concept 3.psd | Dec 2005 |

Todo: New Concept 4 (navy, 800px framed), OUTSIDEBOX purple, dispersion, notebook header, masthead; live content (real comics, forum link); per-theme sidebar/Upload button from the layers.
