/**
 * LamGame Optimized Banner - Dynamic Content Management
 * 4-Slide Carousel with Real-time Data Updates
 */

class LamGameBanner {
  constructor() {
    this.currentSlide = 0;
    this.slides = null;
    this.dots = null;
    this.track = null;
    this.arrows = null;
    this.autoplayTimer = null;
    this.isPlaying = true;
    this.updateInterval = null;
    
    // API endpoints - cập nhật theo Laravel routes
    this.endpoints = {
      jobs: '/api/banner/jobs',
      topics: '/api/banner/topics', 
      blogs: '/api/banner/blogs',
      sources: '/api/banner/sources'
    };
    
    this.init();
  }
  
  init() {
    this.setupElements();
    this.setupEventListeners();
    this.startAutoplay();
    this.loadDynamicContent();
    this.startContentUpdater();
  }
  
  setupElements() {
    this.track = document.getElementById('banner-track');
    this.slides = Array.from(this.track?.children || []);
    this.dots = document.querySelectorAll('.banner-dot');
    this.arrows = {
      prev: document.querySelector('.banner-arrow.prev'),
      next: document.querySelector('.banner-arrow.next')
    };
    
    if (this.slides.length === 0) {
      console.warn('LamGame Banner: No slides found');
      return;
    }
  }
  
  setupEventListeners() {
    // Arrow navigation
    this.arrows.prev?.addEventListener('click', () => this.prevSlide());
    this.arrows.next?.addEventListener('click', () => this.nextSlide());
    
    // Dots navigation
    this.dots.forEach((dot, index) => {
      dot.addEventListener('click', () => this.goToSlide(index));
      dot.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.goToSlide(index);
        }
      });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') this.prevSlide();
      if (e.key === 'ArrowRight') this.nextSlide();
    });
    
    // Pause on hover
    const banner = document.querySelector('.hero-optimized');
    banner?.addEventListener('mouseenter', () => this.pauseAutoplay());
    banner?.addEventListener('mouseleave', () => this.resumeAutoplay());
    
    // Touch/swipe support for mobile
    this.setupTouchEvents();
  }
  
  setupTouchEvents() {
    let startX = 0;
    let startTime = 0;
    
    this.track?.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX;
      startTime = Date.now();
      this.pauseAutoplay();
    });
    
    this.track?.addEventListener('touchend', (e) => {
      const endX = e.changedTouches[0].clientX;
      const endTime = Date.now();
      const diffX = startX - endX;
      const diffTime = endTime - startTime;
      
      // Swipe detection (minimum 50px movement in under 300ms)
      if (Math.abs(diffX) > 50 && diffTime < 300) {
        if (diffX > 0) {
          this.nextSlide();
        } else {
          this.prevSlide();
        }
      }
      
      this.resumeAutoplay();
    });
  }
  
  goToSlide(index) {
    this.currentSlide = (index + this.slides.length) % this.slides.length;
    this.updateSlidePosition();
    this.updateDots();
  }
  
  nextSlide() {
    this.goToSlide(this.currentSlide + 1);
  }
  
  prevSlide() {
    this.goToSlide(this.currentSlide - 1);
  }
  
  updateSlidePosition() {
    if (!this.track) return;
    const translateX = -this.currentSlide * 100;
    this.track.style.transform = `translateX(${translateX}%)`;
  }
  
  updateDots() {
    this.dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === this.currentSlide);
      dot.setAttribute('aria-label', `Đi đến slide ${index + 1}${index === this.currentSlide ? ' (hiện tại)' : ''}`);
    });
  }
  
  startAutoplay() {
    this.autoplayTimer = setInterval(() => {
      if (this.isPlaying) {
        this.nextSlide();
      }
    }, 6000); // 6 seconds per slide
  }
  
  pauseAutoplay() {
    this.isPlaying = false;
  }
  
  resumeAutoplay() {
    this.isPlaying = true;
  }
  
  stopAutoplay() {
    if (this.autoplayTimer) {
      clearInterval(this.autoplayTimer);
      this.autoplayTimer = null;
    }
  }
  
  // Dynamic Content Management
  async loadDynamicContent() {
    try {
      const [jobsData, topicsData, blogsData, sourcesData] = await Promise.allSettled([
        this.fetchData('jobs'),
        this.fetchData('topics'),
        this.fetchData('blogs'),
        this.fetchData('sources')
      ]);
      
      // Update slide 1: Jobs
      if (jobsData.status === 'fulfilled') {
        this.updateJobsContent(jobsData.value);
      }
      
      // Update slide 2: Forum Topics
      if (topicsData.status === 'fulfilled') {
        this.updateTopicsContent(topicsData.value);
      }
      
      // Update slide 3: Blogs
      if (blogsData.status === 'fulfilled') {
        this.updateBlogsContent(blogsData.value);
      }
      
      // Update slide 4: Sources/Games
      if (sourcesData.status === 'fulfilled') {
        this.updateSourcesContent(sourcesData.value);
      }
      
    } catch (error) {
      console.error('Error loading dynamic content:', error);
    }
  }
  
  async fetchData(type) {
    try {
      const response = await fetch(this.endpoints[type]);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return await response.json();
    } catch (error) {
      console.error(`Error fetching ${type} data:`, error);
      return this.getFallbackData(type);
    }
  }
  
  getFallbackData(type) {
    const fallbackData = {
      jobs: {
        count: Math.floor(Math.random() * 30) + 40,
        companies: ['VNG', 'Gameloft', 'Nexon']
      },
      topics: {
        title: 'Unity vs Unreal cho game mobile?',
        stats: {
          comments: Math.floor(Math.random() * 100) + 50,
          views: Math.floor(Math.random() * 400) + 200,
          likes: Math.floor(Math.random() * 50) + 30
        }
      },
      blogs: {
        title: 'Tối ưu hóa performance Unity cho game 3D',
        author: 'DevUser',
        stats: {
          views: Math.floor(Math.random() * 200) + 100,
          shares: Math.floor(Math.random() * 80) + 20
        }
      },
      sources: {
        project: 'Roguelike Unity Kit',
        idea: 'VR adventure Việt Nam folklore'
      }
    };
    
    return fallbackData[type] || {};
  }
  
  updateJobsContent(data) {
    const jobStatsEl = document.getElementById('job-stats');
    if (jobStatsEl && data.count) {
      jobStatsEl.textContent = `${data.count}+ jobs tuần này`;
    }
  }
  
  updateTopicsContent(data) {
    const hotTopicEl = document.getElementById('hot-topic');
    const topicStatsEl = document.getElementById('topic-stats');
    
    if (hotTopicEl && data.title) {
      hotTopicEl.textContent = `'${data.title}'`;
    }
    
    if (topicStatsEl && data.stats) {
      const { comments, views, likes } = data.stats;
      topicStatsEl.textContent = `${comments} comments, ${views} views, ${likes} likes`;
    }
  }
  
  updateBlogsContent(data) {
    const newBlogEl = document.getElementById('new-blog');
    const blogStatsEl = document.getElementById('blog-stats');
    
    if (newBlogEl && data.title) {
      newBlogEl.textContent = `'${data.title}'`;
    }
    
    if (blogStatsEl && data.stats) {
      const { views, shares } = data.stats;
      blogStatsEl.textContent = `${views} views, ${shares} shares`;
    }
  }
  
  updateSourcesContent(data) {
    const newSourceEl = document.getElementById('new-source');
    const newIdeaEl = document.getElementById('new-idea');
    
    if (newSourceEl && data.project) {
      newSourceEl.textContent = `'${data.project}'`;
    }
    
    if (newIdeaEl && data.idea) {
      newIdeaEl.textContent = `'${data.idea}'`;
    }
  }
  
  startContentUpdater() {
    // Update content every 5 minutes
    this.updateInterval = setInterval(() => {
      this.loadDynamicContent();
    }, 300000);
  }
  
  destroy() {
    this.stopAutoplay();
    
    if (this.updateInterval) {
      clearInterval(this.updateInterval);
    }
    
    // Remove event listeners
    this.arrows.prev?.removeEventListener('click', this.prevSlide);
    this.arrows.next?.removeEventListener('click', this.nextSlide);
  }
}

// Animation counter for stats
class CounterAnimation {
  static animate(element, target, duration = 2000) {
    const start = parseInt(element.textContent) || 0;
    const range = target - start;
    const startTime = performance.now();
    
    function update(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      
      // Easing function
      const easeOutQuart = 1 - Math.pow(1 - progress, 4);
      const current = Math.floor(start + range * easeOutQuart);
      
      element.textContent = current;
      
      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        element.textContent = target;
      }
    }
    
    requestAnimationFrame(update);
  }
}

// Initialize banner when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  // Check if banner exists before initializing
  if (document.querySelector('.hero-optimized')) {
    window.lamGameBanner = new LamGameBanner();
    
    // Animate stats counters on page load
    const statNumbers = document.querySelectorAll('[data-target]');
    statNumbers.forEach(stat => {
      const target = parseInt(stat.dataset.target);
      if (target) {
        // Delay animation slightly for better effect
        setTimeout(() => {
          CounterAnimation.animate(stat, target);
        }, 500);
      }
    });
  }
});

// Export for module usage if needed
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { LamGameBanner, CounterAnimation };
}