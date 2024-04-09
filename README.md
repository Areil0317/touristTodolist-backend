# Todolist backend

清單樂旅後端

## 安裝

1. 將 .env.example 複製為 .env
2. composer install
3. php artisan migrate 
4. 視啟動的路徑與方法不同，可能需要對應修改[前端專案](https://github.com/SVAkkao/frontend---todolist)的 REACT_APP_API_HOST= 數值。

### 關於 4，如何與 REACT_APP_API_HOST= 對應

* 如果是在 xampp/lampp 的話，請依照 http://localhost/{專案名稱}/public/api 路徑複製。比方說，如果在 htdocs git clone 專案的話，路徑通常是： http://localhost/---todolist-backend/public/api
* 如果是使用 php artisan serve 的話，請依照 artisan 命令列指示。比方說： http://localhost:3000/api
