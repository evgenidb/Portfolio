namespace MusicAlbums.Repositories
{
    using MusicAlbums.Model;
    using System;
    using System.Data.Entity;
    using System.Linq;
    using System.Linq.Expressions;

    public class DbAlbumsRepository : IRepository<Album>
    {
        private const string EntityName = "Album";

        private DbContext dbContext;
        private DbSet<Album> entitySet;

        public DbAlbumsRepository(DbContext dbContext)
        {
            this.dbContext = dbContext;
            this.entitySet = this.dbContext.Set<Album>();
        }

        public Album Add(Album item)
        {
            if (item == null || item.Title == null)
            {
                string exceptionMessage = string.Format("The passed {0} or its title should not be null.", EntityName);
                throw new ArgumentNullException(exceptionMessage);
            }

            this.entitySet.Add(item);
            this.dbContext.SaveChanges();
            return item;
        }

        public Album Get(int id)
        {
            return this.entitySet.Find(id);
        }

        public IQueryable<Album> GetAll()
        {
            return this.entitySet;
        }

        public IQueryable<Album> GetAllFull()
        {
            string exceptionMessage = string.Format("The {0} repository doesn't have GetAllFull method.", EntityName);
            throw new NotImplementedException(exceptionMessage);
        }

        public IQueryable<Album> Find(Expression<Func<Album, int, bool>> predicate)
        {
            return this.entitySet.Where(predicate);
        }

        public Album Update(int id, Album item)
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

        public Album Delete(int id)
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

        public Album Delete(Album item)
        {
            if (item == null)
            {
                string exceptionMessage = string.Format("The passed {0} should not be null.", EntityName);
                throw new ArgumentNullException(exceptionMessage);
            }

            this.entitySet.Remove(item);
            return item;
        }

        public Album AddAndSave(Album item)
        {
            var addedItem = Add(item);
            SaveChanges();

            return addedItem;
        }

        public Album UpdateAndSave(int id, Album item)
        {
            var newItem = Update(id, item);
            SaveChanges();

            return newItem;
        }

        public Album DeleteAndSave(int id)
        {
            var deletedItem = Delete(id);
            SaveChanges();
            return deletedItem;
        }

        public Album DeleteAndSave(Album item)
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
    }
}
