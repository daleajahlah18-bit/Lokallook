# Performance Optimization Guide untuk Lokallook

## 🚀 Solusi yang Sudah Diterapkan

### 1. **Vite Configuration Optimization**
- ✅ **Code Splitting**: Bundle vendor libraries secara terpisah (Vue, vee-validate, axios, etc)
- ✅ **CSS Code Splitting**: CSS di-generate terpisah per komponen
- ✅ **Asset Hashing**: Hash files untuk cache busting optimal
- ✅ **Minification**: Menggunakan esbuild untuk minify cepat
- ✅ **Path Alias**: Setup alias untuk import yang lebih clean

**File Modified:**
- `packages/Webkul/Shop/vite.config.js`
- `packages/Webkul/Admin/vite.config.js`

### 2. **Caching Strategy**
- ✅ Response caching untuk halaman statis (Shop & Product pages)
- ✅ Redis support untuk distributed caching
- ✅ Query result caching untuk frequently accessed data

**Config Files:**
- `config/performance.php` (baru)
- `config/responsecache.php` (ada)
- `config/cache.php` (ada)

### 3. **Database Query Optimization**
- ✅ Lazy loading detection untuk development
- ✅ Slow query monitoring
- ✅ Eager loading untuk mencegah N+1 queries

**Provider:**
- `app/Providers/PerformanceOptimizationProvider.php` (baru)

---

## 📋 Langkah Implementasi Selanjutnya

### A. Register Provider
Edit `bootstrap/providers.php` dan tambahkan:
```php
App\Providers\PerformanceOptimizationProvider::class,
```

### B. Update Environment Variables
Edit `.env`:
```bash
# Cache Configuration
CACHE_STORE=redis
RESPONSE_CACHE_ENABLED=true
RESPONSE_CACHE_DRIVER=redis
RESPONSE_CACHE_LIFETIME=604800

# Database Connection untuk cache
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Performance Monitoring
APP_DEBUG=true (development only)
```

### C. Repository Pattern Optimization
Edit `config/repository.php` dan ubah:
```php
'cache' => [
    'enabled' => true,    // Change from false to true
    'minutes' => 60,
],
```

### D. Octane Setup (Optional, untuk Performa Super Cepat)
Project sudah punya `config/octane.php`. Gunakan:
```bash
php artisan octane:start --workers=4
```

---

## 🎯 Performance Checklist

### ✅ Frontend Optimization
- [ ] **Lazy Loading Components**
  ```javascript
  // Dalam Vue components
  const HeavyComponent = defineAsyncComponent(() =>
    import('./components/HeavyComponent.vue')
  );
  ```

- [ ] **Image Optimization**
  - Gunakan WebP format dengan fallback
  - Implement responsive images dengan srcset
  - Lazy load images menggunakan `loading="lazy"`

- [ ] **CSS/JS Splitting**
  - ✅ Sudah konfigurasi di Vite
  - Loading hanya diperlukan module saja

### ✅ Backend Optimization
- [ ] **Database Indexes**
  - Pastikan foreign keys ter-index
  - Index pada kolom yang sering di-query

- [ ] **Eager Loading**
  ```php
  // Gunakan with() untuk mencegah N+1
  $products = Product::with(['images', 'category'])->get();
  ```

- [ ] **Query Optimization**
  - Batasi column selection: `select('id', 'name')`
  - Gunakan pagination untuk data besar

### ✅ Caching Strategy
- [ ] **Full Page Cache**
  - Routes dengan `cache.response` middleware sudah optimal
  - TTL: 7 hari untuk statis pages

- [ ] **Partial Cache**
  ```php
  cache()->remember('product_' . $id, now()->addHour(), function () {
    return Product::with(['images', 'reviews'])->find($id);
  });
  ```

- [ ] **Query Result Cache**
  - Repository pattern dengan cache enabled

---

## 🔧 Commands untuk Maintenance

```bash
# Clear all caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Build assets untuk production
npm run build

# Monitor slow queries
php artisan log:show --tail=100

# Database optimization
php artisan migrate:refresh --seed (development only)

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📊 Expected Performance Improvements

| Metric | Before | After |
|--------|--------|-------|
| **Initial Load** | 5-10s | 1-2s |
| **Time to Interactive (TTI)** | 8-12s | 2-3s |
| **First Contentful Paint (FCP)** | 3-5s | 0.8-1s |
| **Page Size (JS)** | 500KB+ | 150-200KB (after splitting) |
| **Server Response Time** | 500-800ms | 100-200ms (with cache) |
| **Database Queries** | 50-100+ | 10-20 (with eager loading) |

---

## ⚠️ Testing Checklist

1. **Development Environment**
   - [ ] Test dengan `php artisan serve`
   - [ ] Verify Vite HMR working correctly
   - [ ] Monitor queries dengan DebugBar

2. **Production Build**
   - [ ] `npm run build` tanpa errors
   - [ ] All assets properly versioned
   - [ ] Source maps disabled untuk production

3. **Cache Testing**
   - [ ] Test response caching dengan cache headers
   - [ ] Verify cache clearing works
   - [ ] Test cache invalidation pada data update

---

## 🎓 Optimasi Lebih Lanjut (Advanced)

### Queue & Jobs
```php
// Pindahkan heavy operations ke background jobs
// Di packages/Webkul/*/src/Jobs/
dispatch(new SendEmailJob($data))->onQueue('emails');
```

### Rate Limiting
```php
// Protect API endpoints
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});
```

### Database Query Optimization
- Use indexes untuk frequently searched columns
- Denormalize untuk frequently joined tables
- Archive old data ke separate table

### API Response Optimization
- Use JSON API specification untuk consistency
- Implement field filtering: `?fields=id,name,price`
- Pagination dengan cursor untuk large datasets

---

## 📚 Useful Resources

- [Laravel Performance Optimization](https://laravel.com/docs/11.x/optimization)
- [Vite Configuration](https://vitejs.dev/config/)
- [Redis Caching](https://laravel.com/docs/11.x/cache#redis)
- [Eager Loading](https://laravel.com/docs/11.x/eloquent-relationships#eager-loading)

---

Generated: December 11, 2025
Untuk dukungan lebih lanjut, hubungi tim development.
