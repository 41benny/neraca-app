// Theme toggle functionality
export function initTheme() {
  const ls = localStorage.getItem('theme');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const theme = ls ? ls : (prefersDark ? 'dark' : 'light');
  if (theme === 'dark') {
    document.documentElement.classList.add('dark');
  }
}

export function toggleTheme() {
  const root = document.documentElement;
  const isDark = root.classList.toggle('dark');
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

// Initialize theme as soon as possible
if (typeof window !== 'undefined') {
  initTheme();
  // Also watch for system theme changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
      if (e.matches) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    }
  });
}