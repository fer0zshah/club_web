// home-events.js
document.addEventListener("DOMContentLoaded", () => {
    renderHomeContests();
    renderHomeWorkshops();
});

function renderHomeContests() {
    const grid = document.getElementById('contestCardGrid');
    if (!grid) return; 
    
    fetch('get-home-contests.php')
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
            return res.json();
        })
        .then(data => {
            grid.innerHTML = ''; 
            
            if (!data || data.length === 0) {
                grid.innerHTML = '<p style="color: #aaa; grid-column: 1/-1; text-align: center;">No contests announced yet.</p>';
                return;
            }

            data.forEach(contest => {
                const isToday = new Date(contest.start_time).toDateString() === new Date().toDateString();
                const displayDate = isToday ? 'Today' : contest.formatted_date;
                const displayTime = `${contest.start_clock} - ${contest.end_clock}`;
                
                let statusBadgeClass = contest.status === 'ongoing' ? 'status-ongoing' : 
                                       contest.status === 'completed' ? 'status-completed' : 'status-upcoming';
                
                let badgeDot = contest.status === 'ongoing' ? '<span class="pulse-dot"></span>' : '';
                
                let btnText = "Participate Contest";
                let btnClass = "btn-fire";
                
                if (contest.status === 'completed') {
                    btnText = "View Standings";
                    btnClass = "btn-fire-outline";
                }

                const card = document.createElement('div');
                card.className = `sgipc-card ${contest.status === 'ongoing' ? 'ongoing-card' : ''}`;
                
                card.innerHTML = `
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <div class="card-date" style="color: #aaa; font-size: 0.9rem; display: flex; align-items: center; gap: 5px;">
                            <svg class="icon-small" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span>${displayDate} &nbsp;•&nbsp; <span style="color: #ff6b00;">${displayTime}</span></span>
                        </div>
                        <div class="card-status ${statusBadgeClass}" style="text-transform: uppercase; font-size: 0.75rem; font-weight: bold; padding: 4px 10px; border-radius: 20px;">
                            ${badgeDot} ${contest.status}
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <h3 class="card-heading" style="margin-bottom: 5px;">${contest.title}</h3>
                        <div style="font-size: 0.8rem; color: #ff6b00; margin-bottom: 15px; font-weight: 600;">
                            Type: ${contest.contest_type}
                        </div>
                        
                        <p class="card-desc" style="color: #ddd; font-size: 0.95rem; margin-bottom: 15px;">
                            ${contest.description}
                        </p>
                        
                        <div class="card-location" style="color: #aaa; font-size: 0.9rem; display: flex; align-items: center; gap: 5px; margin-bottom: 20px;">
                            <svg class="icon-small" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            ${contest.location}
                        </div>
                    </div>
                    
                    <div class="card-footer" style="margin-top: auto;">
                        <a href="${contest.platform_link || '#'}" target="_blank" class="${btnClass}" style="width: 100%; display: block; text-align: center;">
                            ${btnText}
                        </a>
                    </div>
                `;
                grid.appendChild(card);
            });
        })
        .catch(err => {
            console.error("Contest fetch error:", err);
            grid.innerHTML = '<p style="color: #ff4a4a; text-align: center; width:100%;">Failed to load contests.</p>';
        });
}

function renderHomeWorkshops() {
    const grid = document.getElementById('workshopCardGrid');
    if (!grid) return; 
    
    fetch('get-home-workshops.php')
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
            return res.json();
        })
        .then(data => {
            grid.innerHTML = '';
            
            if (!data || data.length === 0) {
                grid.innerHTML = '<p style="color: #aaa; grid-column: 1/-1; text-align: center;">No upcoming bootcamps scheduled.</p>';
                return;
            }

            data.forEach(workshop => {
                const isToday = new Date(workshop.start_time).toDateString() === new Date().toDateString();
                const displayDate = isToday ? 'Today' : workshop.formatted_date;
                const displayTime = `${workshop.start_clock} - ${workshop.end_clock}`;
                
                // Dynamic variable resolution maps to database entries correctly
                let statusBadgeClass = workshop.status === 'ongoing' ? 'status-ongoing' : 
                                       workshop.status === 'completed' ? 'status-completed' : 'status-upcoming';
                
                let badgeDot = workshop.status === 'ongoing' ? '<span class="pulse-dot"></span>' : '';
                
                let btnText = "Participate Workshop";
                let btnClass = "btn-fire";
                
                if (workshop.status === 'completed') {
                    btnText = "View Details";
                    btnClass = "btn-fire-outline";
                }

                const card = document.createElement('div');
                card.className = `sgipc-card ${workshop.status === 'ongoing' ? 'ongoing-card' : ''}`;
                
                card.innerHTML = `
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <div class="card-date" style="color: #aaa; font-size: 0.9rem; display: flex; align-items: center; gap: 5px;">
                            <svg class="icon-small" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span>${displayDate} &nbsp;•&nbsp; <span style="color: #4CAF50;">${displayTime}</span></span>
                        </div>
                        <div class="card-status ${statusBadgeClass}" style="text-transform: uppercase; font-size: 0.75rem; font-weight: bold; padding: 4px 10px; border-radius: 20px;">
                            ${badgeDot} ${workshop.status || 'upcoming'}
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <h3 class="card-heading" style="margin-bottom: 5px;">${workshop.title}</h3>
                        
                        <div style="font-size: 0.85rem; color: #4CAF50; margin-bottom: 10px; font-weight: 600;">
                            Mentor: ${workshop.mentor_name}
                        </div>
                        
                        <p class="card-desc" style="color: #ddd; font-size: 0.95rem; margin-bottom: 15px;">
                            ${workshop.description}
                        </p>
                        
                        <div class="card-location" style="color: #aaa; font-size: 0.9rem; display: flex; align-items: center; gap: 5px; margin-bottom: 20px;">
                            <svg class="icon-small" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            ${workshop.location}
                        </div>
                    </div>
                    
                    <div class="card-footer" style="margin-top: auto;">
                        <a href="${workshop.materials_link || '#'}" target="_blank" class="${btnClass}" style="width: 100%; display: block; text-align: center;">
                            ${btnText}
                        </a>
                    </div>
                `;
                grid.appendChild(card);
            });
        })
        .catch(err => {
            console.error("Workshop fetch error:", err);
            grid.innerHTML = '<p style="color: #ff4a4a; text-align: center; width:100%;">Failed to load workshops.</p>';
        });
}