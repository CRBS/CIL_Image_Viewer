# CIL_Image_Viewer

![CIL Image Viewer](https://spirilli.crbs.ucsd.edu/images/cil_image_viewer_demo.JPG)

This image viewer utilizes the Leaflet library and the CodeIgniter PHP library for browsing the high definition images.
This image viewer is capable of displaying confocal images, electron tomography z-stack images, and time-series images. 
This user-interface includes image controls such as zooming, panning, contrast adjustment, brightness adjustment, moving 
Z-stack location, moving the time location and the annotation drawing capabilities. Each of these events will trigger 
the web-service call in Javascript to fetch the latest data.