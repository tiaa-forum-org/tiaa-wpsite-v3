#!/bin/bash

# Navigate to the screenshots directory
cd screenshots

# Create output directories if they don't exist
mkdir -p resized/replit-desktop
mkdir -p resized/replit-mobile

# Process desktop screenshots (resize to 25% of original)
echo "Processing desktop screenshots..."
for img in replit-desktop/*.png; do
    if [ -f "$img" ]; then
        filename=$(basename "$img")
        echo "  Processing $filename..."
        ffmpeg -i "$img" -vf "scale=iw*0.25:ih*0.25" -update 1 -y "resized/replit-desktop/$filename"
    fi
done

# Process mobile screenshots (resize to 25% of original)
echo "Processing mobile screenshots..."
for img in replit-mobile/*.png; do
    if [ -f "$img" ]; then
        filename=$(basename "$img")
        echo "  Processing $filename..."
        ffmpeg -i "$img" -vf "scale=iw*0.25:ih*0.25" -update 1 -y "resized/replit-mobile/$filename"
    fi
done

echo "Done! Resized images are in screenshots/resized/"
echo "Checking file sizes..."
find resized -type f -name "*.png" | xargs ls -lh
