<x-layouts.app>
    
    <x-ui.navbar />
    
    <x-sections.hero />
    
    <x-sections.roster :students="$students" />
    
    <x-sections.projects :projects="$projects" />
    
    <x-sections.academic :announcements="$announcements" :schedules="$schedules" />
    
    <x-sections.gallery :galleries="$galleries" />
    
    <x-ui.footer />

</x-layouts.app>
