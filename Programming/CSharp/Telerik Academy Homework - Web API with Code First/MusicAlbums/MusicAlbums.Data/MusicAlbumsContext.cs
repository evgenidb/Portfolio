using System;
using System.Data.Entity;
using MusicAlbums.Model;

namespace MusicAlbums.Data
{
    public class MusicAlbumsContext : DbContext
    {
        public DbSet<Artist> Artists { get; set; }

        public DbSet<Song> Songs { get; set; }

        public DbSet<Album> Albums { get; set; }

        #region Constructor
        public MusicAlbumsContext()
            : base("MusicAlbumsDb")
        { }
        #endregion Constructor
    }
}
