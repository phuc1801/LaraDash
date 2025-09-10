document.addEventListener("alpine:init", () => {
    Alpine.data("userStats", () => ({
        value: 0,
        async loadUserCount() {
            console.log("🔄 Fetching user count...");
            try {
                const res = await fetch("http://127.0.0.1:8000/api/users/count");
                const data = await res.json();
                this.value = data.total_users;
                console.log("✅ Data loaded:", this.value);
            } catch (err) {
                console.error("❌ Error loading user count:", err);
            }
        }
    }));
});
