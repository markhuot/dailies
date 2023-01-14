const plugin = require('tailwindcss/plugin')

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.svg",
        "./resources/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                'dashboard-primary': 'var(--dashboard-primary)',
                'dashboard-primary-highlight': 'var(--dashboard-primary-highlight)',
                'dashboard-primary-dim': 'var(--dashboard-primary-dim)',
                'dashboard-border': 'var(--dashboard-border)',
                'dashboard-highlight': 'var(--dashboard-highlight)',
                'dashboard-highlight-overlay': 'var(--dashboard-highlight-overlay)',
                'dashboard-dim': 'var(--dashboard-dim)',
            },
        },
    },
    plugins: [
        plugin(function({ addUtilities, addVariant, addComponents, e, config }) {
            addUtilities({
                '.bg-radial-gradient': {
                    backgroundImage: 'radial-gradient(ellipse farthest-side at center bottom, var(--tw-gradient-stops))',
                }
            })
        }),
        plugin(function({ addUtilities, addVariant, addComponents, e, config }) {
            addVariant('peer-unchecked', '*:not(:checked)~&')
            addVariant('has-checked', '&:has(:checked)')
            addVariant('has-unchecked', '&:has(:not(:checked))')
        }),
        plugin(function({ addUtilities, addVariant, addComponents, e, config, theme }) {
            for (const key of [
                'dashboard-primary',
                'dashboard-primary-highlight',
                'dashboard-primary-dim',
                'dashboard-border',
                'dashboard-highlight',
                'dashboard-highlight-overlay',
                'dashboard-dim',
            ]) {
                addUtilities(Object.entries(theme('colors')).map(([color, value]) => {
                    if (typeof value === 'string') {
                        return {
                            [`.set-${key}-${color}`]: {
                                [`--${key}`]: value,
                            },
                        }
                    }
                    if (typeof value === 'object') {
                        return Object.entries(value).reduce((obj, [shade, colorValue]) => {
                            obj[`.set-${key}-${color}-${shade}`] = {
                                [`--${key}`]: colorValue,
                            }
                            return obj
                        }, {})
                    }

                    return {}
                }))
            }
        }),
    ],
}
