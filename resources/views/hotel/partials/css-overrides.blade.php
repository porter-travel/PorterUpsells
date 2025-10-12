@php
    if (! function_exists('ems_normalize_hex_color')) {
        function ems_normalize_hex_color(?string $color, string $fallback = '#000000'): string
        {
            $normalized = $color ? trim($color) : '';
            $normalized = ltrim($normalized, '#');

            if (strlen($normalized) === 3) {
                $normalized = $normalized[0] . $normalized[0]
                    . $normalized[1] . $normalized[1]
                    . $normalized[2] . $normalized[2];
            }

            if (! preg_match('/^[0-9a-fA-F]{6}$/', $normalized)) {
                return ems_normalize_hex_color($fallback, '#000000');
            }

            return '#' . strtoupper($normalized);
        }
    }

    if (! function_exists('ems_hex_to_rgb')) {
        function ems_hex_to_rgb(string $hex): array
        {
            $hex = ems_normalize_hex_color($hex);

            return [
                hexdec(substr($hex, 1, 2)),
                hexdec(substr($hex, 3, 2)),
                hexdec(substr($hex, 5, 2)),
            ];
        }
    }

    if (! function_exists('ems_relative_luminance')) {
        function ems_relative_luminance(array $rgb): float
        {
            [$r, $g, $b] = array_map(function ($component) {
                $value = $component / 255;

                return $value <= 0.03928
                    ? $value / 12.92
                    : pow(($value + 0.055) / 1.055, 2.4);
            }, $rgb);

            return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
        }
    }

    if (! function_exists('ems_contrast_ratio')) {
        function ems_contrast_ratio(string $colorA, string $colorB): float
        {
            $lumA = ems_relative_luminance(ems_hex_to_rgb($colorA));
            $lumB = ems_relative_luminance(ems_hex_to_rgb($colorB));

            $lighter = max($lumA, $lumB) + 0.05;
            $darker = min($lumA, $lumB) + 0.05;

            return $lighter / $darker;
        }
    }

    if (! function_exists('ems_pick_accessible_color')) {
        function ems_pick_accessible_color(string $background, ?string $preferred = null, string $light = '#FFFFFF', string $dark = '#111827'): string
        {
            $background = ems_normalize_hex_color($background);
            $preferred = $preferred ? ems_normalize_hex_color($preferred) : null;
            $light = ems_normalize_hex_color($light);
            $dark = ems_normalize_hex_color($dark);

            if ($preferred && ems_contrast_ratio($background, $preferred) >= 4.5) {
                return $preferred;
            }

            $lightContrast = ems_contrast_ratio($background, $light);
            $darkContrast = ems_contrast_ratio($background, $dark);

            return $lightContrast >= $darkContrast ? $light : $dark;
        }
    }

    $pageBackground = ems_normalize_hex_color($hotel->page_background_color ?? '#FFFFFF', '#FFFFFF');
    $mainBoxBackground = ems_normalize_hex_color($hotel->main_box_color ?? '#F5D6E1', '#F5D6E1');
    $buttonBackground = ems_normalize_hex_color($hotel->button_color ?? '#D4F6D1', '#D4F6D1');
    $accentBackground = ems_normalize_hex_color($hotel->accent_color ?? '#C7EDF2', '#C7EDF2');

    $pageTextColor = ems_pick_accessible_color($pageBackground, $hotel->text_color ?? null);
    $mainBoxTextColor = ems_pick_accessible_color($mainBoxBackground, $hotel->main_box_text_color ?? null, $pageTextColor);
    $buttonTextColor = ems_pick_accessible_color($buttonBackground, $hotel->button_text_color ?? null);
    $accentTextColor = ems_pick_accessible_color($accentBackground, null, '#FFFFFF', $pageTextColor);

    $accentBackgroundTransparent = sprintf('#%s80', substr($accentBackground, 1));
@endphp

<style>
    body {
        background-color: {{ $pageBackground }};
        color: {{ $pageTextColor }};
    }

    .hotel-text-color {
        color: {{ $pageTextColor }};
    }

    .hotel-main-box-color {
        background-color: {{ $mainBoxBackground }};
        color: {{ $mainBoxTextColor }};
    }

    .hotel-main-box-text-color {
        color: {{ $mainBoxTextColor }};
    }

    .hotel-button-color {
        background-color: {{ $buttonBackground }};
        color: {{ $buttonTextColor }};
    }

    .hotel-button-text-color {
        color: {{ $buttonTextColor }};
    }

    .hotel-accent-color {
        background-color: {{ $accentBackground }};
        color: {{ $accentTextColor }};
    }

    .hotel-accent-text-color {
        color: {{ $accentTextColor }};
    }

    .hotel-accent-color-50 {
        background-color: {{ $accentBackgroundTransparent }};
    }

    .hotel-button-color,
    .hotel-main-box-color {
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .hotel-button-color:hover,
    .hotel-button-color:focus {
        filter: brightness(0.95);
    }
</style>
