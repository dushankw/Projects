" Dushan's .vimrc
" http://dushan.it

" General Editor Setup
set background=dark
syntax on 
filetype indent plugin on
set autoindent 
set ts=4 sw=4 et si 
set whichwrap+=<,>,[,] 
set backspace=indent,eol,start 
set hlsearch
set number
set laststatus=2
set ruler
set expandtab
set showmode

" Mappings
map <F1> :tab all <CR>
map <F2> :tabp <CR>
map <F3> :tabn <CR>
map <F4> :vsplit <CR>
map <F5> :wa! <CR>
map <F6> :xa! <CR>
map <F7> :qa! <CR>
