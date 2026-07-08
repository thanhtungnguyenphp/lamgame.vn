/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/views/home-v2/**/*.blade.php",
        "./resources/views/layouts/master-v2.blade.php",
        "./resources/views/components/v2/**/*.blade.php",
    ],

    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: "1rem",
                sm: "1.5rem",
                lg: "2rem",
            },
            screens: {
                sm: "640px",
                md: "768px",
                lg: "1024px",
                xl: "1280px",
            },
        },

        screens: {
            sm: "640px",
            md: "768px",
            lg: "1024px",
            xl: "1280px",
        },

        extend: {
            colors: {
                // Background
                "lg-bg": {
                    DEFAULT: "#0D0D1A",
                    secondary: "#1A1A2E",
                    tertiary: "#252540",
                },
                // Accent (Purple)
                "lg-accent": {
                    DEFAULT: "#8B5CF6",
                    light: "#A78BFA",
                    dark: "#7C3AED",
                    subtle: "rgba(139, 92, 246, 0.1)",
                },
                // Text
                "lg-text": {
                    DEFAULT: "#FFFFFF",
                    secondary: "#A1A1AA",
                    muted: "#71717A",
                },
                // Border
                "lg-border": {
                    DEFAULT: "#2E2E4A",
                    light: "#3D3D5C",
                },
                // Status
                "lg-success": "#10B981",
                "lg-warning": "#F59E0B",
                "lg-danger": "#EF4444",
                "lg-info": "#3B82F6",
            },

            fontFamily: {
                inter: ["Inter", "system-ui", "-apple-system", "sans-serif"],
            },

            fontSize: {
                "hero": ["3rem", { lineHeight: "1.1", fontWeight: "700" }],
                "section": ["1.75rem", { lineHeight: "1.3", fontWeight: "600" }],
                "card-title": ["1rem", { lineHeight: "1.4", fontWeight: "600" }],
                "body": ["0.875rem", { lineHeight: "1.5", fontWeight: "400" }],
                "small": ["0.75rem", { lineHeight: "1.4", fontWeight: "400" }],
                "btn": ["0.875rem", { lineHeight: "1", fontWeight: "500" }],
            },

            borderRadius: {
                "card": "12px",
                "btn": "8px",
                "tag": "6px",
            },

            spacing: {
                "section": "4rem",
                "card-gap": "1.5rem",
                "card-p": "1rem",
            },

            backgroundImage: {
                "accent-gradient": "linear-gradient(135deg, #8B5CF6, #6366F1)",
                "hero-gradient": "radial-gradient(ellipse at 30% 50%, rgba(139, 92, 246, 0.15) 0%, transparent 50%)",
            },

            boxShadow: {
                "card": "0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -2px rgba(0, 0, 0, 0.2)",
                "card-hover": "0 10px 25px -5px rgba(139, 92, 246, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.3)",
                "glow": "0 0 20px rgba(139, 92, 246, 0.3)",
            },

            animation: {
                "fade-in": "fadeIn 0.5s ease-out",
                "slide-up": "slideUp 0.5s ease-out",
                "count-up": "countUp 1s ease-out",
            },

            keyframes: {
                fadeIn: {
                    "0%": { opacity: "0" },
                    "100%": { opacity: "1" },
                },
                slideUp: {
                    "0%": { opacity: "0", transform: "translateY(20px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                countUp: {
                    "0%": { opacity: "0", transform: "translateY(10px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
            },
        },
    },

    plugins: [],
};
