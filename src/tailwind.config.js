/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./index.php"],
  theme: {
    extend: {
      fontFamily: { 
        playfair: ['Playfair Display', 'serif'], 
        dm: ['DM Sans', 'sans-serif'] 
      },
      colors: {
        // Tema CREAM dengan text kontras tinggi
        cream: '#fefbf3',           // Background utama
        'cream-dark': '#f5f0e6',    
        'cream-light': '#fffdf9',   
        taupe: '#8b7b5f',           // TEXT UTAMA ⭐ (mudah dibaca)
        'taupe-dark': '#6b5a42',    // Heading
        'taupe-light': '#a89c7e',   
        beige: '#d9c9a2',           
        'beige-dark': '#b8a884',    
        gold: '#d4af37',            
        'gold-light': '#e8c968'     
      },
      animation: {
        'fade-up': {
          '0%': { opacity: '0', transform: 'translateY(30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' }
        }
      }
    }
  },
  plugins: [],
};