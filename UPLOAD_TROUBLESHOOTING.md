# Media Upload Troubleshooting Guide

## Fixed Issues

### 1. ✅ Unrealistic File Size Limit
- **Old**: 5GB max (unrealistic for PHP/web uploads)
- **New**: 2GB max (realistic for XAMPP)
- **Why**: Most PHP servers can't handle 5GB uploads. 2GB is a practical limit.

### 2. ✅ No Upload Progress Feedback
- **Added**: File information display when selecting a file
- **Added**: Progress bar showing file size vs. limit
- **Added**: Submit button shows "Uploading..." state during submission
- **Added**: User warning about not closing the page during upload

### 3. ✅ Poor Error Handling
- **Old**: Silent failures with no feedback
- **New**: Try-catch with detailed error messages
- **New**: File storage validation with feedback
- **New**: Better validation messages

### 4. ✅ Inadequate Timeout Settings
- **Added**: PHP timeout increased to 600 seconds (10 minutes)
- **Added**: Apache/FastCGI timeouts configured
- **Updated**: .htaccess with upload limits

### 5. ✅ Non-Mobile Friendly Form
- **Fixed**: Form inputs now 48px minimum for touch targets
- **Fixed**: Responsive layout for mobile devices
- **Fixed**: Better spacing on small screens
- **Fixed**: Proper grid layout adjustments

## Current Upload Limits (XAMPP)

```
upload_max_filesize: 2GB
post_max_size: 2GB
max_execution_time: 600 seconds (10 minutes)
max_input_time: 600 seconds (10 minutes)
memory_limit: 512MB
```

## Checklist Before Uploading

### PHP Configuration
- ✅ PHP timeout: 600+ seconds
- ✅ Upload max filesize: 2GB+
- ✅ Post max size: 2GB+
- ✅ Memory limit: 512MB+

### Browser Preparation
- ✅ Use Chrome, Firefox, Safari, or Edge
- ✅ Close other browser tabs to free memory
- ✅ Ensure stable internet connection
- ✅ Don't use VPN (can slow down uploads)

### File Selection
- ✅ Select valid video format (MP4, MKV, AVI, MOV, FLV)
- ✅ Ensure file size is under 2GB
- ✅ Check file isn't corrupted

## Troubleshooting Steps

### Issue: Upload Still Loading (Hangs)

**Solution 1: Check Server Status**
```bash
# In terminal, verify Laravel is running
php artisan serve --host=0.0.0.0 --port=8000

# Or if using XAMPP, ensure Apache is running
```

**Solution 2: Verify Storage Permissions**
```bash
# Ensure storage folder has write permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

**Solution 3: Check File Upload to Storage**
- File should appear in `storage/app/media/` after upload
- If not appearing, check storage permissions

**Solution 4: Review Laravel Logs**
```bash
# Check for errors in logs
tail -f storage/logs/laravel.log
```

### Issue: "File Size Exceeds Limit"

- File is larger than 2GB
- Compress video or split into parts
- Use a video converter to reduce file size

### Issue: "Failed to Save File"

- Storage folder not writable
- Disk space full
- File system permissions issue

**Solution:**
```bash
# Fix permissions
chmod -R 777 storage/app/
chmod -R 777 bootstrap/cache/

# Check disk space
df -h
```

### Issue: Upload Takes Very Long

**Normal**: Uploading 2GB can take:
- 1GB on 10Mbps connection: ~13 minutes
- 1GB on 100Mbps connection: ~1.3 minutes
- 500MB on 10Mbps: ~7 minutes

**Tips to Speed Up:**
1. Use wired Ethernet instead of WiFi
2. Close other applications
3. Upload during off-peak hours
4. Use a smaller file size

## Advanced Configuration (Optional)

### Increase XAMPP Limits Further

**Edit `php.ini`** (in XAMPP PHP folder):
```ini
upload_max_filesize = 2G
post_max_size = 2G
max_execution_time = 1200
max_input_time = 1200
memory_limit = 1024M
```

### Enable Chunked Uploads (Future)

For very large files, consider implementing:
1. Chunked file uploads
2. Resume capability
3. Progress tracking
4. Server-side assembly

## File Formats Supported

✅ **Supported Video Formats**
- MP4 (.mp4)
- Matroska (.mkv)
- AVI (.avi)
- MOV (.mov)
- FLV (.flv)

❌ **Not Supported**
- MKA (audio only)
- WebM (needs codec support)
- Raw video files

## Best Practices

1. **Optimize Videos Before Upload**
   - Resolution: 1920x1080 (Full HD) or 1280x720 (HD)
   - Codec: H.264 (most compatible)
   - Bitrate: 2-8 Mbps (depends on resolution)
   - File size: 500MB-2GB

2. **Use Video Compression Tools**
   - HandBrake (free)
   - FFmpeg (command-line)
   - Shotcut (free editor)

3. **Split Large Videos**
   - If video > 2GB, split into parts:
     - Part 1 (1-1.5GB)
     - Part 2 (remaining GB)

4. **Name Your Files Clearly**
   - Use format: `MovieName_Year_Part1.mp4`
   - Avoid special characters
   - Max 255 characters

## Upload Form Features

### Real-time Validation
✅ File selection displays:
- Filename
- File size
- Progress bar relative to 2GB limit
- Percentage of limit used

### Form Assistance
✅ Helpful hints for each field:
- Type selection (Movie/Series)
- Category selection
- Optional metadata fields
- Poster URL for cover image

### User Feedback
✅ During upload:
- Button shows "Uploading..." state
- Loading indicator
- Warning not to close page
- Time estimate (based on connection)

---

**Last Updated**: January 8, 2026
**Version**: 2.0 - Fixed Upload Issues
