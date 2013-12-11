namespace MusicAlbums.Model
{
    using System;
    using System.Collections.Generic;
    using System.ComponentModel.DataAnnotations;

    public class Artist
    {
        public int Id { get; set; }

        [Required]
        [StringLength(100,
            MinimumLength = 1,
            ErrorMessage = "The Artist Name must be between 1 and 100 characters long.")]
        public string Name { get; set; }

        [StringLength(50,
            ErrorMessage = "The Artist Country must be less than 50 characters long.")]
        public string Country { get; set; }

        public DateTime DateOfBirth { get; set; }

        // Navigation Properties
        public virtual ICollection<Song> Songs { get; set; }

        public virtual ICollection<Album> Albums { get; set; }

        public Artist()
        {
            this.Songs = new HashSet<Song>();

            this.Albums = new HashSet<Album>();
        }
    }
}
