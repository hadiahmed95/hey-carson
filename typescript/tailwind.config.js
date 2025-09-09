/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
  theme: {
    extend: {
      colors: {
        primary: 'var(--color-primary)',
        secondary: 'var(--color-secondary)',
        tertiary: 'var(--color-tertiary)',
        'tertiary-dark': 'var(--color-tertiary-dark)',
        accent: 'var(--color-accent)',
        'accent-light': 'var(--color-accent-light)',
        success: 'var(--color-success)',
        'success-light': 'var(--color-success-light)',
        link: 'var(--color-link)',
        'link-light': 'var(--color-link-light)',
        badge: 'var(--color-badge)',
        card: 'var(--color-card)',
        pending: 'var(--color-pending)',
        'pending-light': 'var(--color-pending-light)',
        danger: 'var(--color-danger)',
        grey: 'var(--color-grey)',
        greyDark: 'var(--color-grey-dark)',
        greyExtraDark: 'var(--color-grey-extra-dark)',
        greyLight: 'var(--color-grey-light)',
        softpink: 'var(--color-softpink)',
        darkpink: 'var(--color-darkpink)',
        softgreen: 'var(--color-softgreen)',
        'extra-dark-green': 'var(--color-extra-dark-green)',
        'light-green': 'var(--color-light-green)',
        info: 'var(--color-info)',
        darkGreen: 'var(--color-darkGreen)',
        lightGray: 'var(--color-lightGray)',
        darkGray: 'var(--color-darkGray)',
        brandBlue: 'var(--color-brandBlue)',
        veryLightGray: 'var(--color-veryLightGray)',
        gainsBoro: 'var(--color-gainsboro)',
        coolGray: 'var(--color-coolGray)',
        graniteGray: 'var(--color-graniteGray)',
        lightBlue: 'var(--color-light-blue)',
        linkBlue: 'var(--color-link-blue)',
        linkDarkBlue: 'var(--color-link-dark)',
        lightApricot: 'var(--color-apricot-light)',
        orangeBrown: 'var(--color-orange-brown)',
        babyBlue: 'var(--color-baby-blue)',
        deepBlue: 'var(--color-deep-blue)',
        slateGray: 'var(--color-slate-gray)',
        lightPurple: 'var(--color-light-purple)',
        earthyOrangeBrown: 'var(--color-earthy-orange-brown)',
        deepViolet: 'var(--color-deep-violet)',
        'grey-black-combo': 'var(--color-grey-black)',
        'light-grey': 'var(--color-light-grey)',
      },
      screens: {
        xl: '1200px',
      },
      fontSize: {
        h1: ['32px', '40px'],
        h2: ['24px', '32px'],
        h3: ['18px', '26px'],
        h4: ['14px', '22px'],
        h5: ['13px', '21px'],
        h6: ['12px', '18px'],
        paragraph: ['16px', '24px'],
        custom1: ['12px', '20px'],
        '3xs': '8px',
      },
      fontFamily: {
        archivo: ['var(--font-archivo)', 'sans-serif'],
        sans: ['var(--font-archivo)', 'sans-serif'],
        besley: ['var(--font-besley)', 'sans-serif'],
      },
      fontWeight: {
        thin: '100',
        extraLight: '200',
        light: '300',
        normal: '400',
        medium: '500',
        semibold: '600',
        bold: '700',
        extrabold: '800',
        black: '900',
      },
      borderRadius: {
        sm: '4px',
        md: '8px',
        lg: '12px',
        xl: '16px',
      },
      spacing: {
        'card-padding': '16px',
        'card-padding-md': '24px',
        'card-padding-lg': '32px',
      },
      width: {
        'one-third': '33.333333%',
        '320': '320px',
        '390': '390px',
        '48px': '48px'
      },
    },
    letterSpacing: {
      '10p': '0.1em', // 10% of font size
    },

  },
  plugins: [
    function ({ addBase, theme }) {
      addBase({
        'h1': {
          fontSize: theme('fontSize.h1[0]'),
          lineHeight: theme('fontSize.h1[1]'),
          // fontWeight: theme('fontWeight.bold'),
        },
        'h2': {
          fontSize: theme('fontSize.h2[0]'),
          lineHeight: theme('fontSize.h2[1]'),
          // fontWeight: theme('fontWeight.semibold'),
        },
        'h3': {
          fontSize: theme('fontSize.h3[0]'),
          lineHeight: theme('fontSize.h3[1]'),
          // fontWeight: theme('fontWeight.medium'),
        },
        'h4': {
          fontSize: theme('fontSize.h4[0]'),
          lineHeight: theme('fontSize.h4[1]'),
          // fontWeight: theme('fontWeight.medium'),
        },
        'h5': {
          fontSize: theme('fontSize.h5[0]'),
          lineHeight: theme('fontSize.h5[1]'),
          // fontWeight: theme('fontWeight.normal'),
        },
        'h6': {
          fontSize: theme('fontSize.h6[0]'),
          lineHeight: theme('fontSize.h6[1]'),
          // fontWeight: theme('fontWeight.normal'),
        },
        'p': {
          fontSize: theme('fontSize.paragraph[0]'),
          lineHeight: theme('fontSize.paragraph[1]'),
          // fontWeight: theme('fontWeight.light'),
        },
      });
    }
  ]
}
