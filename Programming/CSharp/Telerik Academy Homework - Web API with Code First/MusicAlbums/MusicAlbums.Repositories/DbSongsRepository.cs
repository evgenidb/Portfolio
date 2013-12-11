namespace MusicAlbums.Repositories
{
    using MusicAlbums.Model;
    using System;
    using System.Data;
    using System.Data.Entity;
    using System.Linq;
    using System.Linq.Expressions;

    public class DbSongsRepository : IRepository<Song>
    {
        private const string EntityName = "Song";

        private DbContext dbContext;
        private DbSet<Song> entitySet;

        public DbSongsRepository(DbContext dbContext)
        {
            this.dbContext = dbContext;
            this.entitySet = this.dbContext.Set<Song>();
        }

        public Song Add(Song item)
        {
            if (item == null || item.Title == null)
            {
                string exceptionMessage = string.Format("The passed {0} or its title should not be null.", EntityName);
                throw new ArgumentNullException(exceptionMessage);
            }

            this.entitySet.Add(item);
            return item;
        }

        public Song AddAndSave(Song item)
        {
            var addedItem = Add(item);
            SaveChanges();

            return addedItem;
        }

        public Song Get(int id)
        {
            return this.entitySet.Find(id);
        }

        public IQueryable<Song> GetAll()
        {
            return this.entitySet;
        }

        public IQueryable<Song> GetAllFull()
        {
            return this.entitySet.Include(s => s.Artist);
        }

        public IQueryable<Song> Find(Expression<Func<Song, int, bool>> predicate)
        {
            return this.entitySet.Where(predicate);
        }

        public Song Update(int id, Song item)
        {
            if (item == null || item.Title == null)
            {
                string exceptionMessage = string.Format("The passed {0} or its title should not be null.", EntityName);
                throw new ArgumentNullException(exceptionMessage);
            }

            if (!id.Equals(item.Id))
            {
                string exceptionMessage = string.Format("The passed Id and the passed {0} Id should be equal.", EntityName);
                throw new ArgumentException(exceptionMessage);
            }

            var oldItem = this.entitySet.Find(id);
            oldItem = item;

            // this.dbContext.Entry(item).State = EntityState.Modified;
            
            // this.entitySet.Attach(item);
            // this.dbContext.SaveChanges();
            return item;
        }

        public Song UpdateAndSave(int id, Song item)
        {
            var newItem = Update(id, item);
            SaveChanges();

            return newItem;
        }

        public Song Delete(int id)
        {
            var item = this.entitySet.Find(id);
            if (item == null)
            {
                string exceptionMessage = string.Format("No {0} found with this Id.", EntityName);
                throw new NullReferenceException(exceptionMessage);
            }

            this.entitySet.Remove(item);
            return item;
        }

        public Song Delete(Song item)
        {
            if (item == null)
            {
                string exceptionMessage = string.Format("The passed {0} should not be null.", EntityName);
                throw new ArgumentNullException(exceptionMessage);
            }

            this.entitySet.Remove(item);
            return item;
        }

        public Song DeleteAndSave(int id)
        {
            var deletedItem = Delete(id);
            SaveChanges();

            return deletedItem;
        }

        public Song DeleteAndSave(Song item)
        {
            var deletedItem = Delete(item);
            SaveChanges();

            return deletedItem;
        }

        public void Dispose()
        {
            this.entitySet = null;
            this.dbContext.Dispose();
        }

        public void SaveChanges()
        {
            this.dbContext.SaveChanges();
        }

        
        //public void DiscardChanges(Song item)
        //{
        //    this.dbContext.Entry(item).CurrentValues.SetValues(this.dbContext.Entry(item).OriginalValues);
        //    //you may also need to set back to unmodified -
        //    //I'm unsure if EF will do this automatically
        //    this.dbContext.Entry(item).State = EntityState.Unchanged;
        //}
    }
}
