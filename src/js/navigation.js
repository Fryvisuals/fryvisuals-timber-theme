import $ from 'jquery';

export const navigation = () => {
  const navigation = document.querySelector('.header');
  const menuItems = navigation.querySelectorAll('.menu-item');
  const subMenuParent = navigation.querySelectorAll('.menu-item-has-children');
  const primaryMenu = navigation.querySelector('.primary-menu');
  const searchIcon = navigation.querySelector('.menu-buttons__search');
  const searchField = navigation.querySelector('.menu-buttons__search-field');
  const hamburgerIcon = navigation.querySelector('.hamburger');
  const dropdownImages = navigation.querySelectorAll('.nav-drop__image');

  function handleSubMenuClick(e, item, menuItems) {
    if (window.innerWidth <= 768) {
      let target = e.target;
      e.preventDefault();
      e.stopPropagation();
      item.classList.toggle('active');
      navigation.classList.toggle('sub-active');
    }
  }

  hamburgerIcon.addEventListener('click', () => {
    primaryMenu.classList.toggle('active');
    navigation.classList.toggle('toggled');
  });

  subMenuParent.forEach((item) => {
    item.querySelector('.menu-item__link').addEventListener('click', (e) =>
      handleSubMenuClick(e, item, menuItems)
    );
  });

  navigation.classList.add('loaded');

  searchIcon.addEventListener('click', () => {
    searchField.toggleAttribute('hidden');

    if( $('.menu-buttons__search-field input').attr('hidden', false)) {
      $('.menu-buttons__search-field input').trigger('focus');
    }
  });
};
