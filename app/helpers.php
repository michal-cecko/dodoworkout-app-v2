<?php

function svgIcon($path, array $attributes = []): false|string
{
    // Icons/SVGs live under public/. Resolve relative paths there so the helper
    // works regardless of the runtime CWD — php-fpm serves from public/, but
    // Octane/RoadRunner runs with the project root as CWD.
    $resolved = file_exists($path) ? $path : public_path($path);
    if (!file_exists($resolved)) {
        throw new \InvalidArgumentException("SVG file not found at path: $path");
    }

    // Get the SVG content
    $svgContent = file_get_contents($resolved);

    // Find the opening <svg> tag
    if (preg_match('/<svg\b[^>]*>/i', $svgContent, $matches, PREG_OFFSET_CAPTURE)) {
        $svgTag = $matches[0][0];
        $svgTagPosition = $matches[0][1];

        // Parse existing attributes in the <svg> tag
        $updatedSvgTag = $svgTag;
        foreach ($attributes as $attr => $value) {
            $attrValue = is_array($value) ? implode(" ", $value) : $value;

            if (preg_match('/\b' . preg_quote($attr, '/') . '="[^"]*"/i', $updatedSvgTag)) {
                // Replace the attribute if it already exists
                $updatedSvgTag = preg_replace(
                    '/\b' . preg_quote($attr, '/') . '="[^"]*"/i',
                    "$attr=\"$attrValue\"",
                    $updatedSvgTag
                );
            } else {
                // Add the attribute if it doesn't exist
                $updatedSvgTag = rtrim($updatedSvgTag, '>') . " $attr=\"$attrValue\">";
            }
        }

        // Replace the original <svg> tag with the updated one
        $svgContent = substr_replace($svgContent, $updatedSvgTag, $svgTagPosition, strlen($svgTag));
    }

    return $svgContent;
}
