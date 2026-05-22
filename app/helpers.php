<?php

function svgIcon($path, array $attributes = []): false|string
{
    if (!file_exists($path)) {
        throw new \InvalidArgumentException("SVG file not found at path: $path");
    }

    // Get the SVG content
    $svgContent = file_get_contents($path);

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
